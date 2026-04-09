define([], function () {
    return {
        init: function () {
            var cmId = (function () {
                var m = window.location.search.match(/[?&]id=(\d+)/);
                return m ? m[1] : 'default';
            }());
            var storageKey = 'umc_current_console_' + cmId;
            var sections = ['use', 'modify', 'create'];

            // ── Stepper highlight ────────────────────────────────────────
            function setActiveStep(name) {
                document.querySelectorAll('.umc-stepper [data-umc-step]').forEach(function (node) {
                    node.classList.toggle('is-active', node.dataset.umcStep === name);
                });
            }

            // ── Mostra sezione senza distruggere gli iframe ──────────────
            // Usiamo visibility+height invece di display:none per evitare
            // che il browser scarichi e ricarichi gli iframe nascosti.
            function showSection(name) {
                sections.forEach(function (s) {
                    var el = document.getElementById('console-' + s);
                    if (!el) return;
                    if (s === name) {
                        el.style.visibility = 'visible';
                        el.style.position   = 'static';
                        el.style.height     = 'auto';
                        el.style.overflow   = 'visible';
                    } else {
                        el.style.visibility = 'hidden';
                        el.style.position   = 'absolute';
                        el.style.height     = '0';
                        el.style.overflow   = 'hidden';
                    }
                });
                setActiveStep(name);
                try { localStorage.setItem(storageKey, name); } catch (e) {}
            }

            function getCurrentSection() {
                for (var i = 0; i < sections.length; i++) {
                    var el = document.getElementById('console-' + sections[i]);
                    if (el && el.style.visibility !== 'hidden') return sections[i];
                }
                return 'use';
            }

            // ── CREATE bloccato finché MODIFY non salvato nel DB ─────────
            function isCreateLocked() {
                var el = document.getElementById('console-create');
                return el && el.dataset.locked === '1';
            }

            function showSaveWarning() {
                alert('Prima devi salvare il tuo progetto Modifica su Moodle.\n' +
                      'Ricorda anche di rendere il progetto Pubblico su Snap!.');
            }

            // ── Bottoni NEXT ─────────────────────────────────────────────
            document.querySelectorAll('[data-umc-next]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var target = this.dataset.umcNext;
                    if (target === 'create' && isCreateLocked()) {
                        showSaveWarning();
                        return;
                    }
                    showSection(target);
                });
            });

            // ── Bottoni PREV ─────────────────────────────────────────────
            document.querySelectorAll('[data-umc-prev]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    showSection(this.dataset.umcPrev);
                });
            });

            // ── Click sullo stepper ──────────────────────────────────────
            document.querySelectorAll('.umc-stepper [data-umc-step]').forEach(function (step) {
                step.addEventListener('click', function () {
                    var target = this.dataset.umcStep;
                    if (target === 'create' && isCreateLocked()) {
                        showSaveWarning();
                        return;
                    }
                    showSection(target);
                });
            });

            // ── Sezione iniziale ─────────────────────────────────────────
            var params  = new URLSearchParams(window.location.search);
            var initial = params.get('open') || (function () {
                try { return localStorage.getItem(storageKey); } catch (e) { return null; }
            }()) || 'use';

            if (initial === 'create' && isCreateLocked()) {
                initial = 'modify';
            }

            showSection(initial);

            // ── Checkbox "ho reso pubblico" → abilita Save Modify ────────
            var cbModify  = document.getElementById('umc-modify-public-confirm');
            var btnModify = document.getElementById('umc-modify-submit');
            if (cbModify && btnModify) {
                // Se il progetto è già salvato (bottone già abilitato dal PHP),
                // segna il checkbox come spuntato per coerenza visiva.
                if (!btnModify.disabled) {
                    cbModify.checked = true;
                }
                cbModify.addEventListener('change', function () {
                    btnModify.disabled      = !cbModify.checked;
                    btnModify.style.opacity = cbModify.checked ? '1'       : '0.45';
                    btnModify.style.cursor  = cbModify.checked ? 'pointer' : 'not-allowed';
                });
            }

            // ── Checkbox "ho reso pubblico" → abilita Save Create ────────
            var cbCreate  = document.getElementById('umc-create-public-confirm');
            var btnCreate = document.getElementById('umc-create-submit');
            if (cbCreate && btnCreate) {
                // Se il progetto è già salvato (bottone già abilitato dal PHP),
                // segna il checkbox come spuntato per coerenza visiva.
                if (!btnCreate.disabled) {
                    cbCreate.checked = true;
                }
                cbCreate.addEventListener('change', function () {
                    btnCreate.disabled      = !cbCreate.checked;
                    btnCreate.style.opacity = cbCreate.checked ? '1'       : '0.45';
                    btnCreate.style.cursor  = cbCreate.checked ? 'pointer' : 'not-allowed';
                });
            }
        }
    };
});
