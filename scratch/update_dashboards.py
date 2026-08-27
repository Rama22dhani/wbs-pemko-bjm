import re

admin_path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php'
verifikator_path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\verifikator\dashboard.blade.php'

# 1. Update Admin Dashboard
with open(admin_path, 'r', encoding='utf-8') as f:
    admin_content = f.read()

script_to_insert = """
        // Helper DataTables Client-Side Manager
        function tableManager(tableId, totalRows) {
            return {
                tableId: tableId,
                search: '',
                perPage: 10,
                currentPage: 1,
                totalItems: totalRows,
                filteredCount: totalRows,
                get totalPages() { return Math.ceil(this.filteredCount / this.perPage) || 1; },
                get startIndex() {
                    if (this.filteredCount === 0) return 0;
                    return (this.currentPage - 1) * this.perPage + 1;
                },
                get endIndex() { return Math.min(this.currentPage * this.perPage, this.filteredCount); },
                initTable() { this.updateTable(); },
                updateTable() {
                    const table = document.getElementById(this.tableId);
                    if (!table) return;
                    const rows = Array.from(table.querySelectorAll('tbody tr[data-row="true"]'));
                    const s = this.search.toLowerCase().trim();

                    let matchedRows = [];
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        if (!s || text.includes(s)) { matchedRows.push(row); } else { row.style.display = 'none'; }
                    });

                    this.filteredCount = matchedRows.length;
                    if (this.currentPage > this.totalPages) { this.currentPage = this.totalPages || 1; }
                    if (this.currentPage < 1) this.currentPage = 1;

                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + parseInt(this.perPage);

                    matchedRows.forEach((row, idx) => {
                        if (idx >= start && idx < end) {
                            row.style.display = '';
                            const noCell = row.querySelector('.col-no');
                            if (noCell) noCell.textContent = (idx + 1);
                        } else { row.style.display = 'none'; }
                    });
                },
                nextPage() { if (this.currentPage < this.totalPages) { this.currentPage++; this.updateTable(); } },
                prevPage() { if (this.currentPage > 1) { this.currentPage--; this.updateTable(); } },
                goToPage(p) { this.currentPage = p; this.updateTable(); }
            };
        }

        // Helper Sort Column on Header Click
        function sortTable(tableId, colIndex) {
            const table = document.getElementById(tableId);
            if (!table) return;
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr[data-row="true"]'));
            const currentDir = table.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';
            table.setAttribute('data-sort-dir', currentDir);

            rows.sort((a, b) => {
                const cellA = a.children[colIndex] ? a.children[colIndex].innerText.trim() : '';
                const cellB = b.children[colIndex] ? b.children[colIndex].innerText.trim() : '';
                return currentDir === 'asc' 
                    ? cellA.localeCompare(cellB, undefined, {numeric: true}) 
                    : cellB.localeCompare(cellA, undefined, {numeric: true});
            });

            rows.forEach(r => tbody.appendChild(r));
            const container = table.closest('[x-data]');
            if (container && container._x_dataStack && container._x_dataStack[0]) { container._x_dataStack[0].updateTable(); }
        }
"""

if "function tableManager" not in admin_content:
    admin_content = admin_content.replace(
        "        function getFileBadgeInfo(url) {",
        script_to_insert + "\n        function getFileBadgeInfo(url) {"
    )

pattern = re.compile(r'(<div x-show="tab === \'(.*?)\'".*?)(<div class="overflow-x-auto">.*?</table>\s*</div>)', re.DOTALL)

def replace_td(match):
    attrs = match.group(1)
    # Extract existing class
    class_match = re.search(r'class="([^"]*)"', attrs)
    if class_match:
        existing_class = class_match.group(1)
        new_attrs = attrs.replace(class_match.group(0), '')
        return f'<td{new_attrs} class="{existing_class} border border-slate-200">'
    return f'<td{attrs} class="border border-slate-200">'

def process_table(match):
    prefix = match.group(1)
    tab_name = match.group(2)
    table_html = match.group(3)
    
    if tab_name in ['beranda', 'laporan']:
        return match.group(0)

    data_var_map = {
        'master_pegawai': '$dataMasterPegawai',
        'pegawai': '$dataPegawai',
        'pengguna': '$dataPengguna',
        'kasus': '$dataKasus',
        'investigasi': '$dataKasus',
        'tindaklanjut': '$dataKasus',
        'bukti': '$dataKasus'
    }
    data_var = data_var_map.get(tab_name, '$dataKasus')

    if 'x-data=' not in prefix:
        prefix = prefix.replace(f'x-show="tab === \'{tab_name}\'"', f'x-show="tab === \'{tab_name}\'" x-data="tableManager(\'table-{tab_name}\', {{{{ count({data_var}) }}}})" x-init="initTable()"')
    else:
        prefix = re.sub(r'x-data="\{', f'x-data="{{ ...tableManager(\'table-{tab_name}\', {{{{ count({data_var}) }}}}), ', prefix)
        if 'initTable()' not in prefix:
            prefix = prefix.replace('x-data="', 'x-init="initTable()" x-data="')

    top_controls = f'''
    <div class="px-5 py-3.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Show</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>entries</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Search:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Cari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
    '''
    if "Search:" not in table_html:
        table_html = table_html.replace('<div class="overflow-x-auto">', top_controls + '<div class="overflow-x-auto">', 1)

    if f'id="table-{tab_name}"' not in table_html:
        table_html = table_html.replace('<table class="w-full text-left border-collapse">', f'<table id="table-{tab_name}" class="w-full text-left border-collapse border border-slate-200">', 1)

    def replace_th(th_match):
        attrs = th_match.group(1)
        content = th_match.group(2)
        idx = getattr(replace_th, 'idx', 0)
        replace_th.idx = idx + 1
        if "⇅" in content: return th_match.group(0)
        attrs = re.sub(r'class="[^"]*"', '', attrs)
        return f'<th{attrs} class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable(\'table-{tab_name}\', {idx})">{content} <span class="text-slate-400">⇅</span></th>'
    
    replace_th.idx = 0
    table_html = re.sub(r'<th\b([^>]*)>(.*?)</th>', replace_th, table_html, flags=re.DOTALL)
    
    table_html = re.sub(r'<tr class="hover:bg-slate-50 transition">', r'<tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">', table_html)
    
    # td border using simple regex replacement
    table_html = re.sub(r'<td\b([^>]*)>', replace_td, table_html)

    footer = '''
    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Showing <span class="font-bold text-slate-800" x-text="startIndex"></span> to <span class="font-bold text-slate-800" x-text="endIndex"></span> of <span class="font-bold text-slate-800" x-text="filteredCount"></span> entries
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Previous</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? \'bg-amber-500 text-white font-bold\' : \'bg-white text-slate-700 hover:bg-slate-100\'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Next</button>
        </div>
    </div>
    '''
    if "Showing <span" not in table_html:
        table_html = table_html.replace('</table>\n                    </div>', '</table>\n                    </div>' + footer)
    
    return prefix + table_html

admin_content = pattern.sub(process_table, admin_content)

with open(admin_path, 'w', encoding='utf-8') as f:
    f.write(admin_content)
print("Admin dashboard updated successfully!")

# 2. Revert Verifikator Dashboard
with open(verifikator_path, 'r', encoding='utf-8') as f:
    v = f.read()

v = re.sub(r'// Helper DataTables Client-Side Manager.*?// Perbarui tampilan pagination & nomor.*?}\s*}\s*}', '', v, flags=re.DOTALL)
v = re.sub(r'x-data="tableManager[^"]*"\s*x-init="initTable\(\)"', '', v)
v = re.sub(r'<!-- DataTables Top Controls.*?</div>\s*</div>', '', v, flags=re.DOTALL)
v = re.sub(r'<!-- DataTables Bottom Footer.*?</div>\s*</div>', '', v, flags=re.DOTALL)

v = v.replace('<table id="table-verifikasi" class="w-full text-left border-collapse border border-slate-200">', '<table class="w-full text-left border-collapse">')
v = v.replace('<table id="table-tindaklanjut" class="w-full text-left border-collapse border border-slate-200">', '<table class="w-full text-left border-collapse">')
v = v.replace('<table id="table-riwayat" class="w-full text-left border-collapse border border-slate-200">', '<table class="w-full text-left border-collapse">')

v = re.sub(r'onclick="sortTable\([^"]+\)"', '', v)
v = re.sub(r'<span class="text-slate-400">⇅</span>', '', v)
v = re.sub(r'class="[^"]*border border-slate-200[^"]*"', 'class="py-3 px-4 border-b border-slate-200"', v)
v = re.sub(r'data-row="true"', '', v)

with open(verifikator_path, 'w', encoding='utf-8') as f:
    f.write(v)

print("Verifikator dashboard reverted successfully!")
