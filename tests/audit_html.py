import urllib.request
import re

routes = ['/', '/about', '/programme', '/register', '/contact']
for r in routes:
    html = urllib.request.urlopen('http://127.0.0.1:8000' + r).read().decode('utf-8')
    h1s = re.findall(r'<h1[^>]*>(.*?)</h1>', html, re.DOTALL)
    imgs = re.findall(r'<img[^>]*>', html)
    imgs_no_alt = [img for img in imgs if 'alt=' not in img]
    forms = re.findall(r'<form[^>]*>', html)
    csrf_count = html.count('name="csrf_token"')
    
    print('Route ' + r + ':')
    print('  H1 count:', len(h1s), '->', (h1s[0].strip().replace('\n', ' ')[:55] if h1s else 'None'))
    print('  Images:', len(imgs), '| Missing alt:', len(imgs_no_alt))
    print('  Forms:', len(forms), '| CSRF tokens:', csrf_count)
