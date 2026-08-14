import { list } from '@vercel/blob';

export default async function handler(req, res) {
  try {
    const existing = await list({ prefix: 'gallery-data.json' });
    if (existing.blobs.length === 0) {
      return res.status(200).json([]);
    }
    const metaRes = await fetch(existing.blobs[0].url);
    const entries = metaRes.ok ? await metaRes.json() : [];
    return res.status(200).json(entries);
  } catch (err) {
    console.error(err);
    return res.status(500).json({ error: err.message });
  }
}
