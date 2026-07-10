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

Alpine.start();
