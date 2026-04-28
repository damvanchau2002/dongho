(function(){
  var API = 'api'; // Using local API routes: /api/provinces and /api/wards

  /* ── Normalize Vietnamese text: remove diacritics & common prefixes ── */
  function normVN(str) {
    if (!str) return '';
    var s = str.replace(/[đĐ]/g, function(c){ return c === 'đ' ? 'd' : 'D'; });
    s = s.normalize('NFD').replace(/[\u0300-\u036f\u1dc0-\u1dff\u20d0-\u20ff]/g, '');
    s = s.toLowerCase().trim().replace(/\./g, '');
    s = s.replace(/^(tinh|thanh pho|tp|quan|huyen|phuong|xa|thi tran|thi xa)\s*/i, '').trim();
    return s;
  }

  function fuzzyMatch(a, b) {
    var na = normVN(a), nb = normVN(b);
    if (!na || !nb || nb.length < 3) return false;
    return na === nb || na.includes(nb) || nb.includes(na);
  }

  /* ── Fill a <select> and try to pre-select by name ── */
  function fillSelect(sel, items, labelKey, codeKey, preselectName) {
    while (sel.options.length > 1) sel.remove(1);
    items.forEach(function(item) {
      var opt = document.createElement('option');
      opt.value       = item[codeKey];
      opt.textContent = item[labelKey];
      opt.dataset.name = item[labelKey];
      sel.appendChild(opt);
    });

    if (preselectName && preselectName.trim()) {
      var found = Array.from(sel.options).find(function(o) {
        return normVN(o.dataset.name) === normVN(preselectName);
      });
      if (!found) {
        found = Array.from(sel.options).find(function(o) {
          return fuzzyMatch(o.dataset.name, preselectName);
        });
      }
      if (found) { sel.value = found.value; return found; }
    }
    return null;
  }

  /* ── Assemble the hidden field & preview ── */
  function rebuildAddr(cfg, provSel, wardSel) {
    var street = (document.getElementById(cfg.streetId) || {value:''}).value.trim();
    var pName  = provSel.selectedIndex > 0 ? (provSel.options[provSel.selectedIndex].dataset.name || '') : '';
    var wName  = wardSel.selectedIndex > 0 ? (wardSel.options[wardSel.selectedIndex].dataset.name || '') : '';
    var pCode  = provSel.value || '';
    var wCode  = wardSel.value || '';

    var parts = [street, wName, pName].filter(Boolean);
    var full  = parts.join(', ');

    var hidden = document.getElementById(cfg.hiddenId);
    if (hidden) hidden.value = full;

    // Update specific parts if IDs provided
    if (cfg.provinceCodeId) {
        var pCodeEl = document.getElementById(cfg.provinceCodeId);
        if (pCodeEl) pCodeEl.value = pCode;
    }
    if (cfg.wardCodeId) {
        var wCodeEl = document.getElementById(cfg.wardCodeId);
        if (wCodeEl) wCodeEl.value = wCode;
    }
    if (cfg.streetPartId) {
        var sPartEl = document.getElementById(cfg.streetPartId);
        if (sPartEl) sPartEl.value = street;
    }

    var preview     = document.getElementById(cfg.previewId);
    var previewText = document.getElementById(cfg.previewTextId);
    if (preview && previewText) {
      if (full) { previewText.textContent = full; preview.style.display = 'flex'; }
      else      { preview.style.display = 'none'; }
    }
  }

  function fetchJSON(url) {
    return fetch(url).then(function(r) { return r.json(); });
  }

  function setLoading(sel, isLoading) {
    if (!sel || !sel.options || sel.options.length === 0) return;
    if (isLoading) {
      sel.disabled = true;
      sel.options[0].textContent = 'Đang tải...';
    } else {
      sel.options[0].textContent = sel.dataset.placeholder;
    }
  }

  window.initAddressPicker = function(cfg) {
    var provSel = document.getElementById(cfg.provinceId);
    var distSel = document.getElementById(cfg.districtId);
    var wardSel = document.getElementById(cfg.wardId);
    if (!provSel || !wardSel) return;

    // Hide district since local data only has Province -> Ward
    if (distSel) {
        distSel.parentElement.style.display = 'none';
    }

    provSel.dataset.placeholder = provSel.options[0] ? provSel.options[0].textContent : '-- Tỉnh / Thành phố --';
    wardSel.dataset.placeholder = wardSel.options[0] ? wardSel.options[0].textContent : '-- Phường / Xã --';

    var existing = cfg.existingAddress || '';
    var preStreet = '', preWard = '', preProv = '';
    if (existing) {
      var parts = existing.split(',').map(function(p){ return p.trim(); });
      if (parts.length >= 3) {
        preProv   = parts[parts.length - 1];
        preWard   = parts[parts.length - 2];
        preStreet = parts.slice(0, parts.length - 2).join(', ');
      } else if (parts.length === 2) {
        preProv = parts[1]; preStreet = parts[0];
      } else {
        preStreet = existing;
      }
    }

    var streetEl = document.getElementById(cfg.streetId);
    if (streetEl && !streetEl.value && preStreet) streetEl.value = preStreet;
    if (streetEl) streetEl.addEventListener('input', function(){ rebuildAddr(cfg, provSel, wardSel); });

    function loadWards(provCode, presel) {
      wardSel.innerHTML = '<option value="">' + wardSel.dataset.placeholder + '</option>';
      wardSel.disabled  = true;
      rebuildAddr(cfg, provSel, wardSel);
      if (!provCode) return;
      
      setLoading(wardSel, true);
      fetchJSON(API + '/wards?province_code=' + provCode)
        .then(function(items) {
          setLoading(wardSel, false);
          fillSelect(wardSel, items, 'name', 'code', presel);
          wardSel.disabled = false;
          rebuildAddr(cfg, provSel, wardSel);
        })
        .catch(function(e) {
          console.error('loadWards error:', e);
          wardSel.innerHTML = '<option value="">⚠ Lỗi tải dữ liệu</option>';
          wardSel.disabled = false;
        });
    }

    provSel.addEventListener('change', function() {
      loadWards(this.value);
    });
    wardSel.addEventListener('change', function() {
      rebuildAddr(cfg, provSel, wardSel);
    });

    // Initial load of provinces
    setLoading(provSel, true);
    fetchJSON(API + '/provinces')
      .then(function(provinces) {
        setLoading(provSel, false);
        var foundProv = fillSelect(provSel, provinces, 'name', 'code', preProv);
        provSel.disabled = false;
        if (foundProv && provSel.value) {
          loadWards(provSel.value, preWard);
        }
      })
      .catch(function(e) {
        console.error('loadProvinces error:', e);
        provSel.innerHTML = '<option value="">⚠ Lỗi tải dữ liệu</option>';
      });
  };
})();
