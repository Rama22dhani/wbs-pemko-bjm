import re

file_path = 'resources/views/verifikator/dashboard.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace Strings
content = content.replace('Administrator Utama', 'Verifikator')
content = content.replace("route('admin.show'", "route('verifikator.show'")
content = content.replace("route('admin.kasus.verifikasi'", "route('verifikator.kasus.verifikasi'")
content = content.replace("route('admin.tindaklanjut.edit'", "route('verifikator.tindaklanjut.edit'")
content = content.replace("route('admin.tindaklanjut.update'", "route('verifikator.tindaklanjut.update'")
content = content.replace("action=\"/admin/kasus/\"", "action=\"/verifikator/kasus/\"")

# Removing the Sidebar Links for Master Data
# Master Data Sidebar section typically has a header "MASTER DATA"
content = re.sub(r'<!-- HEADER MASTER DATA -->.*?<!-- HEADER DATA PENGADUAN -->', '<!-- HEADER DATA PENGADUAN -->', content, flags=re.DOTALL)

# Removing the specific master data sections
content = re.sub(r'<!-- MENU 1: MASTER DATA AKSES.*?<!-- MENU 4: DATA KASUS -->', '<!-- MENU 4: DATA KASUS -->', content, flags=re.DOTALL)

# Fixing the active tab logic
content = content.replace("x-data=\"{ tab: 'akses',", "x-data=\"{ tab: 'kasus',")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print('Dashboard verifikator processed successfully.')
