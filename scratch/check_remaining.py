with open(r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if 'w-6 h-6' in line and any(cls in line or (i>0 and cls in lines[i-1]) for cls in ['bg-blue-50', 'bg-amber-50', 'bg-red-50', 'bg-emerald-50', 'p-1.5', 'p-2', 'p-3', '<button', '<a ']):
        print(f"Remaining w-6 h-6 at line {i+1}: {line.strip()[:60]}")
