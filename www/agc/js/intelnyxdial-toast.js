/**
 * IntelNyx Dial — Toast Notification System
 * Drop-in replacement for browser alert() in the agent UI
 * Usage:
 *   IntelNyxToast.show('Call connected', 'success')
 *   IntelNyxToast.show('Line dropped', 'error')
 *   IntelNyxToast.show('On hold', 'warning')
 *   IntelNyxToast.show('New callback', 'info')
 *   IntelNyxToast.show({ title: 'Disposed', msg: 'Lead marked as Sale', type: 'success' })
 */
(function(global) {
  'use strict';

  var ICONS = {
    success: '✓',
    error:   '✕',
    warning: '⚠',
    info:    'ℹ'
  };

  var LABELS = {
    success: 'Success',
    error:   'Error',
    warning: 'Warning',
    info:    'Info'
  };

  var DEFAULT_DURATION = 4000; // ms

  function getContainer() {
    var el = document.getElementById('intelnyxdial-toast-container');
    if (!el) {
      el = document.createElement('div');
      el.id = 'intelnyxdial-toast-container';
      el.className = 'intelnyxdial-toast-container';
      document.body.appendChild(el);
    }
    return el;
  }

  function show(options, typeArg, durationArg) {
    var title, msg, type, duration;

    if (typeof options === 'string') {
      title    = options;
      msg      = '';
      type     = typeArg     || 'info';
      duration = durationArg || DEFAULT_DURATION;
    } else {
      title    = options.title    || LABELS[options.type] || 'Info';
      msg      = options.msg      || options.message || '';
      type     = options.type     || 'info';
      duration = options.duration || DEFAULT_DURATION;
    }

    var container = getContainer();

    var toast = document.createElement('div');
    toast.className = 'intelnyxdial-toast toast-' + type;

    toast.innerHTML =
      '<span class="toast-icon">' + (ICONS[type] || 'ℹ') + '</span>' +
      '<div class="toast-body">' +
        '<div class="toast-title">' + _esc(title) + '</div>' +
        (msg ? '<div class="toast-msg">' + _esc(msg) + '</div>' : '') +
      '</div>' +
      '<button class="toast-close" aria-label="Dismiss">&#x2715;</button>';

    container.appendChild(toast);

    // Dismiss on close button
    toast.querySelector('.toast-close').addEventListener('click', function() {
      dismiss(toast);
    });

    // Auto-dismiss
    var timer = setTimeout(function() { dismiss(toast); }, duration);

    // Pause on hover
    toast.addEventListener('mouseenter', function() { clearTimeout(timer); });
    toast.addEventListener('mouseleave', function() {
      timer = setTimeout(function() { dismiss(toast); }, 1500);
    });

    return toast;
  }

  function dismiss(toast) {
    if (!toast || toast.classList.contains('toast-out')) return;
    toast.classList.add('toast-out');
    setTimeout(function() {
      if (toast.parentNode) toast.parentNode.removeChild(toast);
    }, 280);
  }

  function _esc(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  global.IntelNyxToast = { show: show, dismiss: dismiss };

})(window);
