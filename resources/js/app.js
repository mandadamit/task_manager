import './bootstrap';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

toastr.options = {
  "closeButton": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
};

window.toastr = toastr;
