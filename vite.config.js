import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import fs from "fs";
import path from "path";

function getCssInputs(dir) {
    const root = path.resolve(__dirname);
    const target = path.join(root, dir);
    const result = [];

    function scan(folder) {
        for (const entry of fs.readdirSync(folder)) {
            const full = path.join(folder, entry);
            const stat = fs.lstatSync(full);

            if (stat.isDirectory()) {
                scan(full);
            } else if (full.endsWith(".css")) {
                // Convert absolute → relative path
                result.push(full.replace(root + "/", ""));
            }
        }
    }

    scan(target);
    return result;
}

export default defineConfig({
    plugins: [
        laravel({
            input: [...getCssInputs("resources/css"), "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
