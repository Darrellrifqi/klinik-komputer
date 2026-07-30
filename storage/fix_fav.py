
from PIL import Image

img = Image.open(r'C:/Users/ASUS/.gemini/antigravity-ide/brain/5029ac20-c989-400f-9893-7dc37afb58b5/media__1785384148561.png').convert('RGBA')

w, h = img.size
crop_h = h
for y in range(h - 1, 0, -1):
    has_pixel = False
    for x in range(w):
        if img.getpixel((x, y))[3] > 20:
            has_pixel = True
            break
    if has_pixel:
        crop_h = y + 1
        break

img_trimmed = img.crop((0, 0, w, crop_h))
w2, h2 = img_trimmed.size
side = max(w2, h2)

square_img = Image.new('RGBA', (side, side), (0, 0, 0, 0))
square_img.paste(img_trimmed, ((side - w2) // 2, (side - h2) // 2))

final_img = square_img.resize((128, 128), Image.Resampling.LANCZOS)

for dst in ['favicon-kk.png', 'favicon.ico', 'favicon.png', 'apple-touch-icon.png']:
    final_img.save(r'D:\Internship\Company-Page\klinik-komputer\public/' + dst)

print('FAVICON_FIXED_PERFECTLY')
