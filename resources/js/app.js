import './bootstrap';

import Alpine from 'alpinejs';
import { initCharts } from './charts';

window.Alpine = Alpine;

Alpine.start();

initCharts();
