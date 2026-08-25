import re

file_path = 'resources/views/verifikator/dashboard.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remove destroy forms
content = re.sub(r'<form action="\{\{ route\(\'admin\.[a-z_]+\.destroy\'.*?</form>', '', content, flags=re.DOTALL)

# Remove cetak rekap links
content = re.sub(r'<a href="\{\{ route\(\'admin\.rekap\.cetak\'.*?</a>', '', content, flags=re.DOTALL)

# Remove modal edit kasus (Verifikator should only verify and tindak lanjut, not edit everything)
content = re.sub(r'<!-- Modal Edit Kasus Manual -->.*?</div>\s*</div>\s*</div>', '', content, flags=re.DOTALL)

# Remove Edit Kasus Manual Button
content = re.sub(r'<button @click=\'showModalEditKasus = true;.*?title="Edit Kasus Manual".*?</button>', '', content, flags=re.DOTALL)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print('Cleaned up verifikator dashboard forms.')
