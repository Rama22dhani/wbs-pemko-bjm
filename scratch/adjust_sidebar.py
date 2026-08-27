import os
path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    c = f.read()

# Make sidebar buttons larger
c = c.replace('class="w-full flex items-center gap-3 px-3 py-2 rounded-r-lg text-xs font-medium transition-colors"', 'class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors"')

# Make sidebar icons larger
c = c.replace('<svg class="w-4 h-4 opacity-75"', '<svg class="w-5 h-5 opacity-75"')

# Add more space above section headers
c = c.replace('tracking-wider mb-2 mt-4', 'tracking-wider mb-2 mt-6')
c = c.replace('tracking-wider mb-2 mt-3', 'tracking-wider mb-2 mt-4')

# Increase top padding slightly in sidebar
c = c.replace('<div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">', '<div class="flex-1 overflow-y-auto py-5 px-3 space-y-1">')

with open(path, 'w', encoding='utf-8') as f:
    f.write(c)
print("Sidebar font sizes adjusted")
