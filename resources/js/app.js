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

window.orderProgressStepper = (formId) => ({
    currentStep: 1,

    init() {
        const updateActiveStep = () => {
            const scrollPos = window.scrollY + 220;
            let active = 1;
            for (let i = 1; i <= 5; i++) {
                const el = document.getElementById('section-step-' + i);
                if (el && el.offsetTop <= scrollPos) active = i;
            }
            this.currentStep = active;
        };

        window.addEventListener('scroll', updateActiveStep, { passive: true });
        updateActiveStep();
    },

    scrollToStep(step) {
        const el = document.getElementById('section-step-' + step);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            this.currentStep = step;
        }
    },
});

window.initOrderDraft = ({ formId, storageKey, containerStorageKey }) => {
    const form = document.getElementById(formId);
    const saveButton = document.getElementById('btnSaveDraft');
    const status = document.getElementById('draftStatus');
    const banner = document.getElementById('draftBanner');
    const bannerMeta = document.getElementById('draftBannerMeta');
    const resumeButton = document.getElementById('btnResumeDraft');
    const clearButton = document.getElementById('btnClearDraft');
    const submitButton = document.getElementById('btnSubmitOrder');
    const validationMessage = document.getElementById('formValidationMsg');
    if (!form) return;

    const setStatus = (message) => {
        if (status) status.textContent = message;
    };

    const readDraft = () => {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || 'null');
        } catch (error) {
            localStorage.removeItem(storageKey);
            return null;
        }
    };

    const hideBanner = () => banner?.classList.add('hidden');

    const showBanner = (draft) => {
        if (!banner) return;
        if (bannerMeta && draft?.savedAt) {
            const when = new Date(draft.savedAt);
            bannerMeta.textContent = `Draft terakhir disimpan ${when.toLocaleString('id-ID')}. Pulihkan atau mulai dari awal.`;
        }
        banner.classList.remove('hidden');
    };

    const save = (showNotice = false) => {
        const fields = Array.from(form.elements)
            .filter((field) => {
                if (!field.name || field.disabled || field.name === '_token' || field.type === 'file') return false;
                if (String(field.name).startsWith('groups[')) return false; // disimpan oleh container-input sendiri
                return true;
            })
            .map((field) => ({
                name: field.name,
                type: field.type,
                value: field.value,
                checked: field.checked,
            }));

        try {
            localStorage.setItem(storageKey, JSON.stringify({ fields, savedAt: new Date().toISOString() }));
            setStatus(showNotice ? 'Draft tersimpan di perangkat ini' : 'Draft disimpan otomatis');
        } catch (error) {
            setStatus('Draft tidak dapat disimpan');
        }
    };

    const restore = () => {
        const draft = readDraft();
        if (!draft?.fields?.length) return false;

        draft.fields.forEach((saved) => {
            Array.from(form.elements)
                .filter((field) => field.name === saved.name && !field.disabled)
                .forEach((field) => {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        field.checked = saved.checked;
                    } else {
                        field.value = saved.value;
                    }
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                });

            // Notify Alpine-powered inputs (hidden fields bound with :value).
            window.dispatchEvent(new CustomEvent('order-form-draft-restore', {
                detail: { name: saved.name, value: saved.value },
            }));
        });

        // Picu pemulihan grup kontainer jika ada tersimpan
        window.dispatchEvent(new CustomEvent('order-container-draft-restore'));

        setStatus('Draft dipulihkan dari perangkat ini');
        document.dispatchEvent(new CustomEvent('order-form-draft-restored'));
        return true;
    };

    const clearDraft = () => {
        localStorage.removeItem(storageKey);
        if (containerStorageKey) localStorage.removeItem(containerStorageKey);
    };

    let timer;
    const scheduleSave = () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => save(false), 600);
    };

    form.addEventListener('input', scheduleSave);
    form.addEventListener('change', scheduleSave);
    saveButton?.addEventListener('click', () => save(true));

    resumeButton?.addEventListener('click', () => {
        restore();
        hideBanner();
    });

    clearButton?.addEventListener('click', () => {
        clearDraft();
        form.reset();
        const orderDate = document.getElementById('order_date');
        if (orderDate && !orderDate.value) orderDate.value = new Date().toISOString().slice(0, 10);
        hideBanner();
        setStatus('Draft baru');
        document.dispatchEvent(new CustomEvent('order-form-draft-restored'));
    });

    // ----- Client-side validation before submit -----
    // Disable browser native popup so every missing field gets a visible red state.
    form.noValidate = true;

    const errorTarget = (field) => {
        if (field.type !== 'hidden') return field;
        return field.closest('[x-data]')?.querySelector('button') || field;
    };
    const markInvalid = (field) => errorTarget(field).classList.add('field-invalid');
    const clearInvalid = (field) => errorTarget(field).classList.remove('field-invalid');
    const isRequiredAndVisible = (field) => {
        if (field.disabled) return false;
        if (field.type === 'hidden') return true;
        return field.getClientRects().length > 0;
    };
    const isEmpty = (field) => {
        if (field.type === 'checkbox' || field.type === 'radio') return !field.checked;
        return String(field.value || '').trim() === '';
    };

    form.addEventListener('input', (event) => clearInvalid(event.target));
    form.addEventListener('change', (event) => clearInvalid(event.target));

    form.addEventListener('submit', (event) => {
        form.querySelectorAll('.field-invalid').forEach((element) => element.classList.remove('field-invalid'));

        const invalidFields = Array.from(form.querySelectorAll('[required]'))
            .filter(isRequiredAndVisible)
            .filter(isEmpty);

        if (invalidFields.length > 0) {
            event.preventDefault();
            invalidFields.forEach(markInvalid);

            const first = invalidFields[0];
            const target = errorTarget(first);
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            window.setTimeout(() => target.focus?.({ preventScroll: true }), 300);

            if (validationMessage) {
                validationMessage.classList.remove('hidden');
                window.clearTimeout(validationMessage._hideTimer);
                validationMessage._hideTimer = window.setTimeout(() => validationMessage.classList.add('hidden'), 6000);
            }
            return;
        }

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });

    if (new URLSearchParams(window.location.search).has('clear_storage')) {
        clearDraft();
        setStatus('Draft baru');
        return;
    }

    // Do NOT auto-restore. A fresh "create" page stays empty; the user opts in via the banner.
    const existingDraft = readDraft();
    if (existingDraft?.fields?.length) {
        showBanner(existingDraft);
        setStatus('Ada draft tersimpan');
    } else {
        setStatus('Belum ada draft');
    }
};

Alpine.start();