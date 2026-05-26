import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import os from 'os';

// Obtener la IP local de la red Wi-Fi
function getLocalIP() {
    const interfaces = os.networkInterfaces();
    let backupIP = 'localhost';
    for (const devName in interfaces) {
        const lowerName = devName.toLowerCase();
        if (lowerName.includes('virtual') || 
            lowerName.includes('host-only') || 
            lowerName.includes('vmware') || 
            lowerName.includes('vbox') ||
            lowerName.includes('loopback')) {
            continue;
        }
        const iface = interfaces[devName];
        for (let i = 0; i < iface.length; i++) {
            const alias = iface[i];
            if (alias.family === 'IPv4' && alias.address !== '127.0.0.1' && !alias.internal) {
                // Ignorar subredes típicas de VirtualBox o direcciones locales autoconfiguradas (link-local)
                if (alias.address.startsWith('192.168.56.') || alias.address.startsWith('169.254.')) {
                    continue;
                }
                // Si encontramos una IP en el rango común de routers residenciales, la devolvemos de inmediato
                if (alias.address.startsWith('192.168.1.') || alias.address.startsWith('192.168.0.')) {
                    return alias.address;
                }
                backupIP = alias.address;
            }
        }
    }
    return backupIP;
}

const localIP = getLocalIP();


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: localIP,
        port: 5173,
    },
});
