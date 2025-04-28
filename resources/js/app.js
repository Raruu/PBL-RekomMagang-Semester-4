import './bootstrap';
// Simplebar
import 'simplebar/dist/simplebar.min.js';

// CoreUI
import '@coreui/chartjs/dist/js/coreui-chartjs.js';
import '@coreui/utils/dist/umd/index.js';
import './CoreUI/config.js';
import './CoreUI/color-modes.js';
import * as coreui from '@coreui/coreui'
// @ts-ignore
window.coreui = coreui

// CSRF token setup
import $ from 'jquery';
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// DataTables
import dt from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
