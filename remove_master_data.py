import re

file_path = 'resources/views/verifikator/dashboard.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Delete MENU 0, MENU 1, and MENU 2
content = re.sub(r'<!-- MENU 0: MASTER DATA PEGAWAI.*?<!-- MENU 3: DATA KASUS & INFO TAMBAHAN -->', '<!-- MENU 3: DATA KASUS & INFO TAMBAHAN -->', content, flags=re.DOTALL)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print('Menghapus Menu Master Data Pegawai, Akses, dan Pelapor.')
