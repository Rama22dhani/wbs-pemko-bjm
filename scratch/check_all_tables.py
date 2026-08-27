with open(r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

tables = []
for i, line in enumerate(lines):
    if '<table' in line:
        tables.append((i+1, line.strip()))

for line_no, tbl in tables:
    print(f"Table at line {line_no}: {tbl}")
