import sys

with open(r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if any(cls in line for cls in ['bg-blue-50', 'bg-amber-50', 'bg-red-50', 'bg-emerald-50']):
        safe_line = line.strip()[:80].encode('ascii', errors='replace').decode('ascii')
        print(f"Line {i+1}: {safe_line}")
        for j in range(1, 4):
            if i + j < len(lines):
                safe_sub = lines[i+j].strip()[:80].encode('ascii', errors='replace').decode('ascii')
                print(f"   +{j}: {safe_sub}")
