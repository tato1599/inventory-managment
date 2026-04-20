import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import persist from '@alpinejs/persist'

Alpine.plugin(persist)
window.Alpine = Alpine

Livewire.start()
