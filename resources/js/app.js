import './bootstrap';

import Alpine from 'alpinejs';
import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import 'select2';

window.Alpine = Alpine;
window.$ = window.jQuery = $;
window.DataTable = DataTable;

window.dtFrench = {
    emptyTable: 'Aucune donnée disponible',
    info: 'Affichage de _START_ à _END_ sur _TOTAL_ éléments',
    infoEmpty: 'Aucun élément à afficher',
    infoFiltered: '(filtré sur _MAX_ éléments au total)',
    lengthMenu: 'Afficher _MENU_ éléments',
    loadingRecords: 'Chargement...',
    processing: 'Traitement...',
    search: 'Rechercher :',
    zeroRecords: 'Aucun résultat trouvé',
    paginate: {
        first: 'Premier',
        last: 'Dernier',
        next: 'Suivant',
        previous: 'Précédent',
    },
};

window.trajetsForm = function (old) {
    const emptyTrajet = () => ({ lieu_depart: '', lieu_arrivee: '', date_deplacement: '', moyen_transport: '', cout_estime: '' });
    const initial = old && Object.keys(old).length
        ? Object.values(old).map((t) => ({ ...emptyTrajet(), ...t }))
        : [emptyTrajet()];

    return {
        trajets: initial.map((t, i) => ({ id: i, ...t })),
        nextId: initial.length,
        addTrajet() {
            this.trajets.push({ id: this.nextId++, ...emptyTrajet() });
            this.$nextTick(() => window.initTrajetSelect2());
        },
        removeTrajet(id) {
            if (this.trajets.length > 1) {
                this.trajets = this.trajets.filter((t) => t.id !== id);
            }
        },
        get total() {
            return this.trajets.reduce((sum, t) => sum + (parseFloat(t.cout_estime) || 0), 0);
        },
        formatFCFA(n) {
            return Math.round(n).toLocaleString('fr-FR') + ' FCFA';
        },
    };
};

window.initTrajetSelect2 = function () {
    $('.js-select2-trajet:not(.select2-hidden-accessible)').select2({
        width: '100%',
        placeholder: 'Sélectionner…',
        language: { noResults: () => 'Aucun résultat trouvé' },
    });
};

window.initPeriodFilter = function (tableId, fromId, toId, resetId) {
    $.fn.dataTable.ext.search.push(function (settings, searchData, index) {
        if (settings.nTable.id !== tableId) {
            return true;
        }

        const from = $('#' + fromId).val();
        const to = $('#' + toId).val();
        if (!from && !to) {
            return true;
        }

        const rowDate = $(settings.aoData[index].nTr).attr('data-date');
        if (!rowDate) {
            return true;
        }

        if (from && rowDate < from) {
            return false;
        }
        if (to && rowDate > to) {
            return false;
        }

        return true;
    });

    $('#' + fromId + ', #' + toId).on('change', function () {
        $('#' + tableId).DataTable().draw();
    });

    $('#' + resetId).on('click', function () {
        $('#' + fromId).val('');
        $('#' + toId).val('');
        $('#' + tableId).DataTable().draw();
    });
};

window.wireExportLinks = function (fromId, toId, linkIds) {
    const updateLinks = function () {
        const from = $('#' + fromId).val();
        const to = $('#' + toId).val();

        linkIds.forEach(function (linkId) {
            const link = document.getElementById(linkId);
            if (!link) {
                return;
            }
            const url = new URL(link.dataset.baseHref || link.href, window.location.origin);
            link.dataset.baseHref = url.pathname;
            if (from) {
                url.searchParams.set('from', from);
            } else {
                url.searchParams.delete('from');
            }
            if (to) {
                url.searchParams.set('to', to);
            } else {
                url.searchParams.delete('to');
            }
            link.href = url.pathname + url.search;
        });
    };

    $('#' + fromId + ', #' + toId).on('change', updateLinks);
    updateLinks();
};

Alpine.start();
