$ErrorActionPreference = 'Stop'
$baseDst = "c:\nando-ukk\public\img\books"
$imgSrc = "c:\nando-ukk\public\img"

if (Test-Path $baseDst) {
    # Move existing local books
    foreach ($i in 1..5) {
        $file = "$imgSrc\book$i.png"
        if (Test-Path $file) {
            Move-Item -Path $file -Destination $baseDst -Force
        }
    }
    
    # Download the 10 images
    $urls = @{
        'atomic_habits.jpg' = 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=400&h=600&q=80'
        'sapiens.jpg' = 'https://images.unsplash.com/photo-1544716278-ca5e3f4cb8c0?auto=format&fit=crop&w=400&h=600&q=80'
        'bumi_manusia.jpg' = 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&h=600&q=80'
        'laskar_pelangi.jpg' = 'https://images.unsplash.com/photo-1495640388908-05fa85288e61?auto=format&fit=crop&w=400&h=600&q=80'
        'subtle_art.jpg' = 'https://images.unsplash.com/photo-1555448248-2571daf6344b?auto=format&fit=crop&w=400&h=600&q=80'
        'cantik_luka.jpg' = 'https://images.unsplash.com/photo-1614113489855-66422ad300a4?auto=format&fit=crop&w=400&h=600&q=80'
        'filosofi_teras.jpg' = 'https://images.unsplash.com/photo-1476275466078-4007374efac4?auto=format&fit=crop&w=400&h=600&q=80'
        '1984.jpg' = 'https://images.unsplash.com/photo-1627917056086-bfd9ce1baadd?auto=format&fit=crop&w=400&h=600&q=80'
        'dunia_sophie.jpg' = 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=400&h=600&q=80'
        'laut_bercerita.jpg' = 'https://images.unsplash.com/photo-1647413524672-9657b9d62d29?auto=format&fit=crop&w=400&h=600&q=80'
    }

    foreach ($name in $urls.Keys) {
        $dest = "$baseDst\$name"
        if (!(Test-Path $dest)) {
            Write-Host "Downloading $name..."
            [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
            Invoke-WebRequest -Uri $urls[$name] -OutFile $dest
        }
    }
}

# Hapus ExtraBookSeeder
if (Test-Path 'c:\nando-ukk\database\seeders\ExtraBookSeeder.php') {
    Remove-Item 'c:\nando-ukk\database\seeders\ExtraBookSeeder.php' -Force
}

# Hapus script ini sendiri
Remove-Item $PSCommandPath -Force
