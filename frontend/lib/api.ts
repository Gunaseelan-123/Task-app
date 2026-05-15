const API_BASE = process.env.NEXT_PUBLIC_API_URL ?? "http://127.0.0.1:8000/api/v1";

export async function apiGet<T>(path: string): Promise<T | null> {
  try {
    const response = await fetch(`${API_BASE}${path}`, {
      next: { revalidate: 120 },
      headers: {
        Accept: "application/json",
      },
    });

    if (!response.ok) {
      return null;
    }

    return (await response.json()) as T;
  } catch {
    return null;
  }
}
