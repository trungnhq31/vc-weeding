<script setup lang="ts">
import { ref, onMounted, watch, onUnmounted } from 'vue';
import { MapPin, Star, Sparkles, Building2, CheckCircle2 } from 'lucide-vue-next';

interface VendorItem {
  id: string;
  name: string;
  category: string;
  category_name?: string;
  vibe_label?: string;
  city?: string;
  district?: string;
  price_label?: string;
  rating?: number;
  match_score?: number;
  latitude?: number;
  longitude?: number;
  capacity_text?: string;
  highlights?: string[];
  halls?: Array<{ name: string; capacity: string; price: string; highlight: string }>;
  packages?: Array<{ name: string; price: string; features: string[] }>;
}

const props = defineProps<{
  vendors: VendorItem[];
  selectedVendorId?: string;
}>();

const emit = defineEmits<{
  (e: 'select-vendor', vendor: VendorItem): void;
  (e: 'book-vendor', vendor: VendorItem): void;
}>();

const mapContainerRef = ref<HTMLElement | null>(null);
let mapInstance: any = null;
let markersGroup: any = null;

const activeVendor = ref<VendorItem | null>(null);

const getCategoryEmoji = (cat: string) => {
  const map: Record<string, string> = {
    venue: '🏛️',
    studio: '📸',
    photography: '📸',
    bridal: '👗',
    attire: '👗',
    florist: '💐',
    decor: '💐',
    makeup: '💄',
    catering: '🍷',
  };
  return map[cat] || '📍';
};

const loadLeafletAssets = (): Promise<void> => {
  return new Promise((resolve) => {
    if ((window as any).L) {
      resolve();
      return;
    }

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);

    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = () => resolve();
    document.head.appendChild(script);
  });
};

const initMap = async () => {
  await loadLeafletAssets();

  const L = (window as any).L;
  if (!L || !mapContainerRef.value) return;

  if (mapInstance) {
    mapInstance.remove();
    mapInstance = null;
  }

  // Centered at Ho Chi Minh City (10.7769, 106.7009)
  mapInstance = L.map(mapContainerRef.value, {
    center: [10.7769, 106.7009],
    zoom: 13,
    zoomControl: true,
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(mapInstance);

  markersGroup = L.layerGroup().addTo(mapInstance);
  renderMarkers();

  // Initial selection if selectedVendorId is provided
  if (props.selectedVendorId) {
    zoomToVendorById(props.selectedVendorId);
  }
};

const renderMarkers = () => {
  const L = (window as any).L;
  if (!L || !mapInstance || !markersGroup) return;

  markersGroup.clearLayers();

  props.vendors.forEach((v) => {
    const lat = v.latitude || 10.7769;
    const lng = v.longitude || 10.7009;
    const emoji = getCategoryEmoji(v.category);
    const isSelected = activeVendor.value?.id === v.id;

    const customIcon = L.divIcon({
      className: 'custom-leaflet-vendor-pin',
      html: `
        <div class="px-3 py-1.5 rounded-full text-xs font-bold shadow-xl border flex items-center gap-1.5 cursor-pointer transition-all duration-300 ${isSelected ? 'bg-rose-600 text-white border-white scale-110 shadow-rose-500/50 ring-4 ring-rose-400/40 z-50' : 'bg-slate-900/95 text-white border-rose-400 hover:bg-rose-900'}">
          <span>${emoji}</span>
          <span class="max-w-[130px] truncate font-semibold">${v.name}</span>
          <span class="bg-rose-500 text-white text-[9px] px-1.5 py-0.2 rounded-full font-extrabold">${v.match_score || 95}%</span>
        </div>
      `,
      iconSize: [160, 36],
      iconAnchor: [80, 18],
    });

    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(markersGroup);

    marker.on('click', () => {
      activeVendor.value = v;
      mapInstance.flyTo([lat, lng], 15, { animate: true, duration: 1.0 });
      emit('select-vendor', v);
    });
  });
};

const zoomToVendorById = (vendorId: string) => {
  if (!mapInstance) return;
  const target = props.vendors.find(v => v.id === vendorId);
  if (target) {
    activeVendor.value = target;
    const lat = target.latitude || 10.7769;
    const lng = target.longitude || 10.7009;
    mapInstance.flyTo([lat, lng], 15, { animate: true, duration: 1.2 });
    renderMarkers();
  }
};

watch(() => props.selectedVendorId, (newId) => {
  if (newId) {
    zoomToVendorById(newId);
  }
});

watch(() => props.vendors, () => {
  renderMarkers();
}, { deep: true });

onMounted(() => {
  initMap();
});

onUnmounted(() => {
  if (mapInstance) {
    mapInstance.remove();
    mapInstance = null;
  }
});
</script>

<template>
  <div class="relative w-full h-full min-h-[580px] rounded-3xl overflow-hidden border border-rose-200 shadow-2xl bg-slate-900 flex flex-col">
    
    <!-- Top Control Bar -->
    <div class="absolute top-4 left-4 right-4 z-20 flex flex-wrap items-center justify-between gap-2 bg-slate-900/90 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-slate-700 text-white text-xs font-bold shadow-lg">
      <div class="flex items-center gap-2">
        <MapPin class="w-4 h-4 text-rose-400 animate-bounce" />
        <span>Bản Đồ OpenStreetMap Vị Trí Đối Tác (TP.HCM)</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded-md border border-emerald-800">
          OPENSTREETMAP LIVE TILES
        </span>
      </div>
    </div>

    <!-- Leaflet Map Canvas Container -->
    <div ref="mapContainerRef" class="w-full h-full min-h-[580px] z-10"></div>

  </div>
</template>
