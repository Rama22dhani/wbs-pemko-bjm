import re

admin_path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php'

with open(admin_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Translate existing occurrences of DataTables controls to Bahasa Indonesia
content = content.replace('<span>Show</span>', '<span>Tampilkan</span>')
content = content.replace('<span>entries</span>', '<span>data</span>')
content = content.replace('<label class="font-semibold text-slate-600">Search:</label>', '<label class="font-semibold text-slate-600">Cari Data:</label>')
content = content.replace('placeholder="Cari..."', 'placeholder="Ketik untuk mencari..."')
content = content.replace('>Previous<', '>Sebelumnya<')
content = content.replace('>Next<', '>Selanjutnya<')

# Replace footer text:
# "Showing <span ...></span> to <span ...></span> of <span ...></span> entries"
content = re.sub(
    r'Showing\s+<span\s+class="font-bold text-slate-800"\s+x-text="startIndex"></span>\s+to\s+<span\s+class="font-bold text-slate-800"\s+x-text="endIndex"></span>\s+of\s+<span\s+class="font-bold text-slate-800"\s+x-text="filteredCount"></span>\s+entries',
    'Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data',
    content
)

# 2. Fix tab `bukti` table id conflict:
# In tab `bukti`, it mistakenly used `table-tindaklanjut`
content = content.replace(
    "tableManager('table-tindaklanjut', {{ count($dataKasus) }}),  showModalEditBukti",
    "tableManager('table-bukti', {{ count($dataKasus) }}),  showModalEditBukti"
)
content = content.replace(
    '<table id="table-tindaklanjut" class="w-full text-left border-collapse border border-slate-200">\n                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">\n                                <tr>\n                                    <th  class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable(\'table-tindaklanjut\', 0)">Kode Kasus',
    '<table id="table-bukti" class="w-full text-left border-collapse border border-slate-200">\n                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">\n                                <tr>\n                                    <th  class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable(\'table-bukti\', 0)">Kode Kasus'
)
content = content.replace("sortTable('table-tindaklanjut', 1)", "sortTable('table-bukti', 1)")
content = content.replace("sortTable('table-tindaklanjut', 2)", "sortTable('table-bukti', 2)")
content = content.replace("sortTable('table-tindaklanjut', 3)", "sortTable('table-bukti', 3)")
content = content.replace("sortTable('table-tindaklanjut', 4)", "sortTable('table-bukti', 4)")

# 3. Add DataTables to tab `tindaklanjut` properly
# Currently tindaklanjut has:
# <div class="overflow-x-auto mt-4">\n                        <table class="w-full text-left border-collapse">
top_controls_tindaklanjut = """
    <div class="px-5 py-3.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
"""

footer_tindaklanjut = """
    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
    </div>
"""

# Fix data count in tindaklanjut:
content = content.replace(
    "tableManager('table-tindaklanjut', {{ count($dataKasus) }}),  showModalEditTindakLanjut",
    "tableManager('table-tindaklanjut', {{ count($dataTindakLanjut) }}),  showModalEditTindakLanjut"
)

# Insert top controls and fix table in tindaklanjut
old_tindaklanjut_table = """                    <div class="overflow-x-auto mt-4">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Kode Kasus</th>
                                    <th class="p-4 min-w-[200px]">Judul Laporan</th>
                                    <th class="p-4">Tingkat</th>
                                    <th class="p-4">Instansi Penindak</th>
                                    <th class="p-4">Tanggal Eksekusi</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>"""

new_tindaklanjut_table = top_controls_tindaklanjut + """                    <div class="overflow-x-auto">
                        <table id="table-tindaklanjut" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 0)">Kode Kasus <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 1)">Judul Laporan <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 2)">Tingkat <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 3)">Instansi Penindak <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 4)">Tanggal Eksekusi <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>"""

if old_tindaklanjut_table in content:
    content = content.replace(old_tindaklanjut_table, new_tindaklanjut_table)
    # add data-row to tr in tindaklanjut
    # Find the closing of tindaklanjut table and add footer
    idx_start = content.find('id="table-tindaklanjut"')
    if idx_start != -1:
        idx_end = content.find('</table>\n                    </div>', idx_start)
        if idx_end != -1:
            segment = content[idx_start:idx_end]
            segment_mod = segment.replace('<tr class="hover:bg-slate-50 transition">', '<tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">')
            content = content[:idx_start] + segment_mod + content[idx_end:]
            # Insert footer
            idx_end_new = content.find('</table>\n                    </div>', idx_start)
            content = content[:idx_end_new + len('</table>\n                    </div>')] + footer_tindaklanjut + content[idx_end_new + len('</table>\n                    </div>'):]

# 4. Add DataTables to tab `master_pegawai` properly
old_master_pegawai_head = """                <!-- MENU 0: MASTER DATA PEGAWAI (BARU) -->
                <div x-show="tab === 'master_pegawai'" x-transition.opacity style="display: none;" 
                    x-data="{ showModalMaster: false, editModeMaster: false, formMaster: { id: '', user_id: '', nip: '', nama_pegawai: '', jenis_kelamin: 'Laki-laki', tempat_lahir: '', tanggal_lahir: '', alamat: '', status_kepegawaian: 'PNS', asal_instansi: '', jabatan: '', nomor_hp: '', status_aktif: 'Aktif' } }">"""

new_master_pegawai_head = """                <!-- MENU 0: MASTER DATA PEGAWAI (BARU) -->
                <div x-show="tab === 'master_pegawai'" x-transition.opacity style="display: none;" 
                    x-init="initTable()" x-data="{ ...tableManager('table-master-pegawai', {{ count($dataMasterPegawai) }}), showModalMaster: false, editModeMaster: false, formMaster: { id: '', user_id: '', nip: '', nama_pegawai: '', jenis_kelamin: 'Laki-laki', tempat_lahir: '', tanggal_lahir: '', alamat: '', status_kepegawaian: 'PNS', asal_instansi: '', jabatan: '', nomor_hp: '', status_aktif: 'Aktif' } }">"""

old_master_pegawai_table = """                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">NIP</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">Jenis Kelamin</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Instansi</th>
                                    <th class="p-4">Jabatan</th>
                                    <th class="p-4 text-center">Akun Terhubung</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>"""

new_master_pegawai_table = top_controls_tindaklanjut + """                    <div class="overflow-x-auto">
                        <table id="table-master-pegawai" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 0)">NIP <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 1)">Nama <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 2)">Jenis Kelamin <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 3)">Status <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 4)">Instansi <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 5)">Jabatan <span class="text-slate-400">⇅</span></th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 text-center">Akun Terhubung</th>
                                    <th class="px-4 py-3 text-xs uppercase font-bold tracking-wider border border-slate-200 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>"""

if old_master_pegawai_head in content:
    content = content.replace(old_master_pegawai_head, new_master_pegawai_head)

if old_master_pegawai_table in content:
    content = content.replace(old_master_pegawai_table, new_master_pegawai_table)
    idx_start = content.find('id="table-master-pegawai"')
    if idx_start != -1:
        idx_end = content.find('</table>\n                    </div>', idx_start)
        if idx_end != -1:
            segment = content[idx_start:idx_end]
            segment_mod = segment.replace('<tr class="hover:bg-slate-50 transition">', '<tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">')
            content = content[:idx_start] + segment_mod + content[idx_end:]
            idx_end_new = content.find('</table>\n                    </div>', idx_start)
            content = content[:idx_end_new + len('</table>\n                    </div>')] + footer_tindaklanjut + content[idx_end_new + len('</table>\n                    </div>'):]

with open(admin_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Replacement complete and saved.")
