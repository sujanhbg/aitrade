import {API_BASE_URL} from "@/services/enpoints";

export const urlGenerator = (url) => {
    if (!url) return url
    return url.includes(API_BASE_URL) ? url : `${API_BASE_URL}/${url.split('/').filter(d => d).join('/')}`;
};