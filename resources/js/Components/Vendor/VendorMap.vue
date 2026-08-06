<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, computed, nextTick } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Supercluster from 'supercluster';

export interface MapVendorItem {
  id: string;
  name: string;
  category: string;
  category_name?: string;
  vibe_category?: string;
  vibe_label?: string;
  city?: string;
  district?: string;
  address?: string | null;
  latitude?: number | null;
  longitude?: number | null;
  price_tier?: string;
  price_label?: string;
  rating?: number;
  capacity_text?: string;
  contact_name?: string | null;
  phone?: string | null;
  email?: string | null;
  portfolio_images?: string[];
  highlights?: string[];
  match_score?: number;
  is_booked?: boolean;
  contract_amount?: number;
  paid_amount?: number;
  payment_status?: string;
}

const props = withDefaults(
  defineProps<{
    vendors: MapVendorItem[];
    selectedVendorId?: string | null;
    height?: string;
    center?: [number, number];
    zoom?: number;
    isFullScreen?: boolean;
  }>(),
  {
    selectedVendorId: null,
    height: '600px',
    center: () => [10.776889, 106.700806], // HCMC Center
    zoom: 12,
    isFullScreen: false,
  }
);

const emit = defineEmits<{
  (e: 'select-vendor', vendor: MapVendorItem): void;
  (e: 'book-vendor', vendor: MapVendorItem): void;
  (e: 'toggle-fullscreen'): void;
}>();

const mapContainer = ref<HTMLDivElement | null>(null);
let map: L.Map | null = null;
let clusterIndex: Supercluster | null = null;
let markersLayer: L.LayerGroup | null = null;
let tileLayerInstance: L.TileLayer | null = null;

const currentTileStyle = ref<'voyager' | 'satellite' | 'dark'>('voyager');

// Filter map valid vendors with coordinates
const validVendors = computed(() => {
  return props.vendors.filter(
    (v) => typeof v.latitude === 'number' && typeof v.longitude === 'number' && !isNaN(v.latitude) && !isNaN(v.longitude)
  );
});

// Category Icons SVG Map
const getCategoryIconSvg = (category: string) => {
  switch (category) {
    case 'venue':
      return '🏛️';
    case 'studio':
      return '📷';
    case 'makeup':
      return '💄';
    case 'florist':
      return '🌸';
    case 'attire':
      return '👗';
    case 'catering':
      return '🍷';
    default:
      return '✨';
  }
};

// Create Supercluster Index
const createClusterIndex = () => {
  clusterIndex = new Supercluster({
    radius: 60,
    maxZoom: 16,
  });

  const points = validVendors.value.map((v) => ({
    type: 'Feature' as const,
    properties: {
      cluster: false,
      vendorId: v.id,
      vendor: v,
    },
    geometry: {
      type: 'Point' as const,
      coordinates: [v.longitude!, v.latitude!],
    },
  }));

  clusterIndex.load(points);
};

// Render Clusters & Pins on Leaflet Map
const updateMapMarkers = () => {
  if (!map || !clusterIndex || !markersLayer) return;

  markersLayer.clearLayers();

  const bounds = map.getBounds();
  const zoom = Math.floor(map.getZoom());

  const bbox: [number, number, number, number] = [
    bounds.getWest(),
    bounds.getSouth(),
    bounds.getEast(),
    bounds.getNorth(),
  ];

  const clusters = clusterIndex.getClusters(bbox, zoom);

  clusters.forEach((feature) => {
    const [lng, lat] = feature.geometry.coordinates;
    const isCluster = feature.properties.cluster;

    if (isCluster) {
      // Render Cluster Badge Marker
      const count = feature.properties.point_count;
      const clusterId = feature.id as number;

      const clusterHtml = `
        <div class="group relative flex items-center justify-center cursor-pointer">
          <div class="absolute -inset-3 rounded-full bg-rose-500/30 blur-md group-hover:bg-rose-500/60 transition duration-300 animate-pulse"></div>
          <div class="relative w-12 h-12 rounded-full bg-gradient-to-tr from-rose-700 via-rose-600 to-pink-500 text-white font-extrabold text-xs flex flex-col items-center justify-center border-2 border-white shadow-xl group-hover:scale-110 transition-transform">
            <span>${count}</span>
            <span class="text-[8px] font-normal leading-none uppercase tracking-wider opacity-95">Đối tác</span>
          </div>
        </div>
      `;

      const clusterIcon = L.divIcon({
        html: clusterHtml,
        className: 'custom-cluster-icon',
        iconSize: [48, 48],
        iconAnchor: [24, 24],
      });

      const clusterMarker = L.marker([lat, lng], { icon: clusterIcon });
      clusterMarker.on('click', () => {
        const expansionZoom = Math.min(clusterIndex!.getClusterExpansionZoom(clusterId), 18);
        map?.flyTo([lat, lng], expansionZoom, { duration: 0.8 });
      });

      markersLayer?.addLayer(clusterMarker);
    } else {
      // Render Individual Vendor Pin Marker
      const vendor: MapVendorItem = feature.properties.vendor;
      const isSelected = props.selectedVendorId === vendor.id;
      const iconEmoji = getCategoryIconSvg(vendor.category);

      const pinHtml = `
        <div class="group relative flex items-center justify-center cursor-pointer">
          <div class="absolute -inset-2 rounded-full ${isSelected ? 'bg-amber-400/80 animate-ping' : 'bg-rose-500/40 group-hover:bg-rose-600/60'} blur-xs transition"></div>
          <div class="relative px-3 py-1.5 rounded-full ${isSelected ? 'bg-gradient-to-r from-amber-500 to-rose-600 ring-4 ring-amber-300 text-white scale-110' : 'bg-slate-900/90 text-white border-2 border-rose-400 group-hover:scale-105'} text-xs font-bold shadow-xl flex items-center gap-1.5 transition-all">
            <span class="text-sm">${iconEmoji}</span>
            <span class="max-w-[110px] truncate font-serif">${vendor.name}</span>
            <span class="px-1.5 py-0.5 rounded-md text-[9px] bg-rose-500 text-white font-extrabold">${vendor.match_score || 95}%</span>
          </div>
        </div>
      `;

      const vendorIcon = L.divIcon({
        html: pinHtml,
        className: 'custom-vendor-pin',
        iconSize: [160, 36],
        iconAnchor: [80, 18],
      });

      const marker = L.marker([lat, lng], { icon: vendorIcon });

      // Rich Popup Card
      const popupHtml = `
        <div class="p-4 space-y-3 min-w-[260px] max-w-[300px] font-sans">
          <div class="flex items-center justify-between">
            <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-900 text-[10px] font-bold uppercase tracking-wider">
              ${vendor.category_name || vendor.category}
            </span>
            <span class="text-xs font-bold text-amber-600">⭐ ${vendor.rating || 4.9}</span>
          </div>
          <div>
            <h4 class="font-serif font-bold text-slate-900 text-sm leading-tight">${vendor.name}</h4>
            <p class="text-[11px] text-slate-500 mt-0.5">📍 ${vendor.address || vendor.district || 'TP.HCM'}</p>
          </div>
          <div class="pt-2 border-t border-rose-100 flex items-center justify-between text-xs">
            <span class="font-extrabold text-rose-800">${vendor.price_label || '150 - 350 Triệu'}</span>
            <button id="popup-book-btn-${vendor.id}" class="px-3 py-1 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-[11px] shadow-sm transition">
              + Chốt Ngay
            </button>
          </div>
        </div>
      `;

      marker.bindPopup(popupHtml);

      marker.on('click', () => {
        emit('select-vendor', vendor);
        setTimeout(() => {
          const btn = document.getElementById(`popup-book-btn-${vendor.id}`);
          if (btn) {
            btn.onclick = (e) => {
              e.stopPropagation();
              emit('book-vendor', vendor);
            };
          }
        }, 50);
      });

      markersLayer?.addLayer(marker);
    }
  });
};

// Set Map Tile Style
const setTileStyle = (style: 'voyager' | 'satellite' | 'dark') => {
  if (!map) return;
  currentTileStyle.value = style;

  if (tileLayerInstance) {
    map.removeLayer(tileLayerInstance);
  }

  let tileUrl = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
  let attribution = '&copy; <a href="https://carto.com/">CARTO</a> &copy; OpenStreetMap';

  if (style === 'satellite') {
    tileUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
    attribution = 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS';
  } else if (style === 'dark') {
    tileUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
    attribution = '&copy; <a href="https://carto.com/">CARTO</a> &copy; OpenStreetMap';
  }

  tileLayerInstance = L.tileLayer(tileUrl, {
    attribution,
    maxZoom: 19,
    subdomains: 'abcd',
  }).addTo(map);
};

const invalidateMapSize = () => {
  if (map) {
    map.invalidateSize();
  }
};

const initMap = () => {
  if (!mapContainer.value) return;

  map = L.map(mapContainer.value, {
    center: props.center,
    zoom: props.zoom,
    zoomControl: false,
  });

  L.control.zoom({ position: 'bottomright' }).addTo(map);

  setTileStyle(currentTileStyle.value);

  markersLayer = L.layerGroup().addTo(map);

  createClusterIndex();
  updateMapMarkers();

  // Auto-fit bounds if we have valid vendors
  if (validVendors.value.length > 0) {
    const bounds = L.latLngBounds(validVendors.value.map((v) => [v.latitude!, v.longitude!]));
    map.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
  }

  map.on('moveend', updateMapMarkers);
  map.on('zoomend', updateMapMarkers);

  setTimeout(invalidateMapSize, 100);
  setTimeout(invalidateMapSize, 300);
  setTimeout(invalidateMapSize, 600);
};

// Pan & Fly to selected vendor
watch(
  () => props.selectedVendorId,
  (newId) => {
    if (!newId || !map) return;
    const vendor = validVendors.value.find((v) => v.id === newId);
    if (vendor && typeof vendor.latitude === 'number' && typeof vendor.longitude === 'number') {
      map.flyTo([vendor.latitude, vendor.longitude], 15, { duration: 1 });
    }
  }
);

watch(
  () => props.isFullScreen,
  () => {
    nextTick(() => {
      setTimeout(invalidateMapSize, 100);
      setTimeout(invalidateMapSize, 300);
    });
  }
);

watch(
  () => props.vendors,
  () => {
    createClusterIndex();
    updateMapMarkers();
    setTimeout(invalidateMapSize, 200);
  },
  { deep: true }
);

const flyToDistrict = (lat: number, lng: number, zoom = 14) => {
  if (map) {
    map.flyTo([lat, lng], zoom, { duration: 1 });
  }
};

const locateUserLocation = () => {
  if (navigator.geolocation && map) {
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const { latitude, longitude } = pos.coords;
        map?.flyTo([latitude, longitude], 15, { duration: 1 });
        L.marker([latitude, longitude])
          .addTo(map!)
          .bindPopup('<b style="padding: 4px; display: block; color: #881337;">📍 Vị trí hiện tại của bạn</b>')
          .openPopup();
      },
      () => {
        alert('Không thể định vị vị trí hiện tại. Vui lòng cho phép truy cập vị trí trên trình duyệt.');
      }
    );
  }
};

onMounted(() => {
  initMap();
  window.addEventListener('resize', invalidateMapSize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', invalidateMapSize);
  if (map) {
    map.remove();
    map = null;
  }
});
</script>

<template>
  <div class="relative w-full h-full min-h-[500px] rounded-3xl overflow-hidden border border-rose-200/80 shadow-xl bg-slate-100">
    <div ref="mapContainer" class="w-full h-full min-h-[500px] z-0"></div>

    <!-- Top Overlay Control Bar -->
    <div class="absolute top-3 left-3 right-3 z-10 flex items-center justify-between gap-2 pointer-events-none">
      <!-- Title Badge (Only when fullscreen) -->
      <div v-if="isFullScreen" class="px-3.5 py-1.5 rounded-xl bg-white/95 backdrop-blur-md border border-rose-100 shadow-md text-xs pointer-events-auto flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
        <span class="font-bold text-rose-950">Bản Đồ Cụm Đối Tác ({{ validVendors.length }} Tọa độ)</span>
      </div>
      <div v-else></div>

      <!-- Tile Switcher & Fullscreen Controls -->
      <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-900/90 backdrop-blur-md border border-white/20 shadow-md pointer-events-auto shrink-0">
        <button
          @click="setTileStyle('voyager')"
          class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition cursor-pointer"
          :class="currentTileStyle === 'voyager' ? 'bg-rose-600 text-white' : 'text-slate-300 hover:text-white'"
        >
          🗺️ Phố
        </button>
        <button
          @click="setTileStyle('satellite')"
          class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition cursor-pointer"
          :class="currentTileStyle === 'satellite' ? 'bg-rose-600 text-white' : 'text-slate-300 hover:text-white'"
        >
          🛰️ Vệ Tinh
        </button>
        <button
          @click="setTileStyle('dark')"
          class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition cursor-pointer"
          :class="currentTileStyle === 'dark' ? 'bg-rose-600 text-white' : 'text-slate-300 hover:text-white'"
        >
          🌙 Dark
        </button>
        <span class="w-px h-3.5 bg-white/20"></span>
        <button
          @click="emit('toggle-fullscreen')"
          class="px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-bold text-[10px] transition cursor-pointer"
        >
          {{ isFullScreen ? '🗗 Thu Nhỏ' : '⛶ Fullscreen' }}
        </button>
      </div>
    </div>

    <!-- Bottom Quick Fly District Bar -->
    <div class="absolute bottom-3 left-3 z-10 hidden sm:flex items-center gap-1 p-1 rounded-xl bg-slate-900/80 backdrop-blur-md border border-white/15 text-[10px] font-bold text-white pointer-events-auto">
      <button @click="locateUserLocation" class="px-2 py-0.5 rounded-lg bg-rose-600 text-white cursor-pointer hover:bg-rose-500">📍 Vị trí tôi</button>
      <span class="text-slate-400 px-1">Tới:</span>
      <button @click="flyToDistrict(10.7768, 106.7008)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-rose-600 cursor-pointer">Q1</button>
      <button @click="flyToDistrict(10.7792, 106.6918)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-rose-600 cursor-pointer">Q3</button>
      <button @click="flyToDistrict(10.8045, 106.6713)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-rose-600 cursor-pointer">Phú Nhuận</button>
      <button @click="flyToDistrict(10.8015, 106.6895)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-rose-600 cursor-pointer">Bình Thạnh</button>
      <button @click="flyToDistrict(10.7258, 106.7118)" class="px-2 py-0.5 rounded-lg bg-white/10 hover:bg-rose-600 cursor-pointer">Q7</button>
    </div>
  </div>
</template>

<style>
.custom-cluster-icon, .custom-vendor-pin {
  background: transparent;
  border: none;
}
.leaflet-popup-content-wrapper {
  border-radius: 1.25rem;
  padding: 0;
  box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
  border: 1.5px solid rgba(244, 63, 94, 0.3);
  background: #ffffff;
}
.leaflet-popup-content {
  margin: 0;
}
.leaflet-container {
  font-family: inherit;
}
</style>
