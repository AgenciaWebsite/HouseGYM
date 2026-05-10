<!-- Search Component -->
<div class="search-wrap">
  <svg class="search-wrap__icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"
    stroke-width="2.5">
    <circle cx="11" cy="11" r="8" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
  </svg>
  <input type="text" class="search-input" id="globalSearch" placeholder="Buscar usuario, rutina, maquinas"
    oninput="handleSearch(this.value)">
  <button class="search-clear-btn" onclick="clearSearch()">&#x2022;</button>

  <div class="search-results hidden" id="searchResults"></div>
</div>

<script>
  /* ── Global Search Logic ── */
  function renderSearchResults(results) {
    const box = document.getElementById('searchResults');
    if (!box) return;
    
    if (!results.length) {
      box.innerHTML = '<div class="search-result-item"><div style="font-size:13px;color:#5a5a5a;">Sin resultados</div></div>';
      return;
    }
    
    box.innerHTML = results.map(item => `
      <div class="search-result-item" onclick="goToSearchResult('${item.tipo}', '${item.id}')">
        <div class="search-result-avatar">${String(item.tipo || '?').charAt(0).toUpperCase()}</div>
        <div>
          <div class="search-result-title">${item.titulo}</div>
          <div class="search-result-detail">${item.detalle}</div>
        </div>
        <span class="search-result-badge">${item.tipo}</span>
      </div>`).join('');
  }

  function goToSearchResult(tipo, id) {
    if (tipo === 'usuario') window.location.href = 'index.php?route=admin_usuarios&id=' + id;
    else if (tipo === 'maquina') window.location.href = 'index.php?route=admin_maquinas';
    else if (tipo === 'ejercicio') window.location.href = 'index.php?route=admin_ejercicios';
  }

  let globalSearchTimer = null;
  function handleSearch(val) {
    const box = document.getElementById('searchResults');
    if (!box) return;
    
    clearTimeout(globalSearchTimer);
    if (val.trim().length < 2) {
      box.classList.add('hidden');
      box.innerHTML = '';
      return;
    }
    
    box.classList.remove('hidden');
    globalSearchTimer = setTimeout(async () => {
      try {
        // apiRequest must be defined in the parent file
        const data = await apiRequest('search&q=' + encodeURIComponent(val.trim()));
        if (data) renderSearchResults(data.results || []);
      } catch (e) {
        renderSearchResults([]);
      }
    }, 250);
  }

  function clearSearch() {
    const input = document.getElementById('globalSearch');
    if (input) input.value = '';
    const box = document.getElementById('searchResults');
    if (box) {
      box.classList.add('hidden');
      box.innerHTML = '';
    }
  }

  // Close search results when clicking outside
  document.addEventListener('click', e => {
    if (!e.target.closest('.search-wrap')) clearSearch();
  });
</script>
