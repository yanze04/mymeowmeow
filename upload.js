import { put, list } from '@vercel/blob';

// Allow a bit more room in the request body for photo uploads
export const config = {
  api: {
    bodyParser: {
      sizeLimit: '8mb',
    },
  },
};

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  try {
    const { imageBase64, filename, title, caption } = req.body;

    if (!imageBase64) {
      return res.status(400).json({ error: 'No image provided' });
    }

    // Strip the "data:image/...;base64," prefix
    const commaIndex = imageBase64.indexOf(',');
    const base64Data = commaIndex >= 0 ? imageBase64.slice(commaIndex + 1) : imageBase64;
    const buffer = Buffer.from(base64Data, 'base64');

    const safeName = (filename || `photo-${Date.now()}.jpg`).replace(/[^a-zA-Z0-9._-]/g, '_');

    // 1) Upload the actual photo
    const photoBlob = await put(`gallery/${Date.now()}-${safeName}`, buffer, {
      access: 'public',
      contentType: 'image/jpeg',
    });

    // 2) Load existing metadata list (if it exists yet)
    let entries = [];
    const existing = await list({ prefix: 'gallery-data.json' });
    if (existing.blobs.length > 0) {
      const metaRes = await fetch(existing.blobs[0].url);
      if (metaRes.ok) {
        entries = await metaRes.json();
      }
    }

    // 3) Add the new entry and save the metadata back
    entries.push({
      src: photoBlob.url,
      title: title || '',
      caption: caption || '',
    });

    await put('gallery-data.json', JSON.stringify(entries), {
      access: 'public',
      addRandomSuffix: false,
      contentType: 'application/json',
      allowOverwrite: true,
    });

    return res.status(200).json({ success: true, entry: entries[entries.length - 1] });
  } catch (err) {
    console.error(err);
    return res.status(500).json({ error: err.message });
  }
}
