import re

admin_path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php'

with open(admin_path, 'r', encoding='utf-8') as f:
    c = f.read()

# 1. Re-adjust Hero Banner
# from: class="bg-gradient-to-r from-slate-950 via-bjm-dark to-slate-900 pt-5 pb-14 px-4 sm:px-6 lg:px-8 border-b-2 border-bjm-gold relative overflow-hidden"
# to: class="bg-gradient-to-r from-slate-950 via-bjm-dark to-slate-900 pt-8 pb-20 px-4 sm:px-6 lg:px-8 border-b-4 border-bjm-gold relative overflow-hidden"
c = c.replace('pt-5 pb-14 px-4 sm:px-6 lg:px-8 border-b-2', 'pt-8 pb-20 px-4 sm:px-6 lg:px-8 border-b-4')
c = c.replace('text-lg sm:text-xl font-bold text-white', 'text-xl sm:text-2xl font-bold text-white')
c = c.replace('text-slate-400 text-xs mt-0.5', 'text-slate-400 text-sm mt-1')

# 2. Re-adjust KPI Cards
c = c.replace('<div class="-mt-8 px-4 sm:px-6 lg:px-8 pb-5">', '<div class="-mt-12 px-4 sm:px-6 lg:px-8 pb-8">')
c = c.replace('gap-3 sm:gap-3.5', 'gap-4 sm:gap-6')
c = c.replace('p-3 sm:p-3.5 flex flex-col', 'p-5 sm:p-6 flex flex-col')
c = c.replace('mb-1.5', 'mb-2')
c = c.replace('text-[11px] font-bold', 'text-xs font-bold')
c = c.replace('mb-0.5', 'mb-1')
c = c.replace('text-xl sm:text-2xl font-black', 'text-2xl sm:text-3xl font-black')
c = c.replace('p-2 bg-', 'p-3 bg-')
c = c.replace('<svg class="w-4 h-4" fill="none"', '<svg class="w-6 h-6" fill="none"')
c = c.replace('text-[10px] text-slate-400', 'text-xs text-slate-500 mt-2')

# 3. Re-adjust Beranda Main Content
c = c.replace('<div class="mt-4 sm:mt-5 bg-white', '<div class="mt-6 bg-white')
c = c.replace('px-5 py-5 border-b', 'px-6 py-6 border-b')
c = c.replace('text-base sm:text-lg font-bold text-slate-800 mb-1', 'text-xl font-bold text-slate-800 mb-2')
c = c.replace('text-slate-500 text-xs mb-4', 'text-slate-600 text-sm mb-6')
c = c.replace('gap-3.5 mt-3', 'gap-6 mt-6')
c = c.replace('rounded-xl p-4 shadow-sm', 'rounded-2xl p-6 shadow-sm')
c = c.replace('gap-2.5 mb-2.5', 'gap-3 mb-4')
c = c.replace('p-2 rounded-lg', 'p-2.5 rounded-xl')
c = c.replace('text-sm font-bold', 'text-base font-bold')
c = c.replace('text-slate-600 text-xs leading-relaxed', 'text-slate-600 text-sm leading-relaxed')
c = c.replace('space-y-1.5 text-slate-600 text-xs', 'space-y-3 text-slate-600 text-sm')
c = c.replace('w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5', 'w-5 h-5 text-emerald-500 shrink-0 mt-0.5')

# 4. Re-adjust other tab headers back slightly to be readable
c = c.replace('px-5 py-3.5 border-b', 'px-6 py-4 border-b')
c = c.replace('text-base font-bold', 'text-lg font-bold')

with open(admin_path, 'w', encoding='utf-8') as f:
    f.write(c)

print("Adjustments made for balanced sizing.")
