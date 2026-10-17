import './bootstrap';

import Alpine from 'alpinejs';
import TomSelect from 'tom-select';

window.Alpine = Alpine;

/**
 * Kota Operasional: dropdown dengan pencarian, bisa diketik nilai baru.
 * Nilai dinormalisasi ke huruf besar agar konsisten dengan data customer.
 */
const initCitySelects = (root = document) => {
    root.querySelectorAll('select[data-city-select]').forEach((el) => {
        if (el.tomselect) return;

        const select = new TomSelect(el, {
            create: true,
            persist: false,
            maxItems: 1,
            placeholder: 'Pilih atau ketik kota...',
            createFilter: (input) => input.trim().length > 0,
            render: {
                option_create: (data, escape) => `<div class="create">Gunakan kota baru <strong>${escape(data.input)}</strong></div>`,
            },
            onChange: (value) => {
                const normalized = String(value || '').toUpperCase();
                el.dispatchEvent(new CustomEvent('customer-city-changed', { detail: normalized, bubbles: true }));
            },
        });

        select.control_input?.setAttribute('placeholder', 'Pilih atau ketik kota...');
    });
};

document.addEventListener('DOMContentLoaded', () => initCitySelects());

Alpine.start();