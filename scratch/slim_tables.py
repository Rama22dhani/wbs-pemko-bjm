import re

admin_path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php'

with open(admin_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Slim down action buttons inside tables (from p-3 to p-1.5, rounded-md)
content = content.replace(
    'class="p-3 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm"',
    'class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-xs"'
)
content = content.replace(
    'class="p-3 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all shadow-sm"',
    'class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-md transition-all shadow-xs"'
)
content = content.replace(
    'class="p-3 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm"',
    'class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs"'
)

# 2. In tables, replace <svg class="w-6 h-6" with <svg class="w-4 h-4" where it's inside action buttons
# Let's target SVG icons inside action buttons specifically or in table cells:
# Replace <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
content = content.replace(
    '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>',
    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>'
)

# Replace edit SVG: d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
content = content.replace(
    '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'
)

# Replace delete SVG: d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
content = content.replace(
    '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'
)

# 3. Slim down table cells (td padding from p-4 to px-3.5 py-2.5 text-xs)
# Let's replace table header padding:
content = content.replace(
    'class="px-4 py-3 text-xs uppercase font-bold tracking-wider',
    'class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider'
)

# Table body cells:
content = content.replace(
    'class="divide-y divide-slate-100 text-sm"',
    'class="divide-y divide-slate-100 text-xs"'
)

# Cell padding replacements:
content = content.replace('class="p-4 pl-6 ', 'class="px-3.5 py-2.5 ')
content = content.replace('class="p-4 text-center pr-6 ', 'class="px-3.5 py-2.5 text-center ')
content = content.replace('class="p-4 text-center ', 'class="px-3.5 py-2.5 text-center ')
content = content.replace('class="p-4 ', 'class="px-3.5 py-2.5 ')
content = content.replace('class="p-4"', 'class="px-3.5 py-2.5"')

# 4. Slim down the header Cetak & Tambah buttons (fix oversized text-lg)
content = content.replace(
    'px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-lg font-bold shadow-md',
    'px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm'
)
content = content.replace(
    'bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-lg font-bold px-5 py-2.5 rounded-lg flex items-center gap-2',
    'bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-lg flex items-center gap-1.5'
)
content = content.replace(
    'bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-sm font-bold px-5 py-2.5 rounded-lg flex items-center gap-2',
    'bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-lg flex items-center gap-1.5'
)

# 5. Slim down DataTables top & bottom controls slightly
content = content.replace(
    'class="px-5 py-3.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600"',
    'class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600"'
)
content = content.replace(
    'class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600"',
    'class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600"'
)

with open(admin_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Tables successfully slimmed down!")
