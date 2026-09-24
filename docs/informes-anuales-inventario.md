# Inventario de Informes Anuales

Fuente local autorizada: `RECURSOS GRÁFICOS - WEB DDNA 2026/Informes anuales/`.

Los enlaces del frontend se obtienen de la Biblioteca de Medios mediante el campo `_ddna_file_id` de cada ficha `documento`; no están hardcodeados en la plantilla.

| Año | Título | Archivo local | URL de WordPress | Estado |
| --- | --- | --- | --- | --- |
| 2025 | Informe Anual 2025 | `Informe-Anual-2025.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2025.pdf` | Disponible |
| 2024 | Informe Anual 2024 | `Informe-Anual-2024.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2024.pdf` | Disponible |
| 2023 | Informe Anual 2023 | `Informe-Anual-2023.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2023.pdf` | Disponible |
| 2022 | Informe Anual 2022 | `Informe-Anual-2022.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2022.pdf` | Disponible |
| 2021 | Informe Anual 2021 | `Informe-Anual-2021.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2021.pdf` | Disponible |
| 2020 | Informe Anual 2020 | `Informe-Anual-2020.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2020.pdf` | Disponible |
| 2019 | Informe Anual 2019 | `Informe-Anual-2019.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2019.pdf` | Disponible |
| 2018 | Informe Anual 2018 | `Informe-Anual-2018.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2018.pdf` | Disponible |
| 2017 | Informe Anual 2017 | `Informe-Anual-2017.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2017.pdf` | Disponible |
| 2016 | Informe Anual 2016 | `Informe-Anual-2016.pdf` | `/wp-content/uploads/2026/09/Informe-Anual-2016.pdf` | Disponible |
| 2016–2026 | Memoria de Gestión 2016–2026 | `Memoria Gestión 2016-2026.pdf` | `/wp-content/uploads/2026/09/Memoria-Gestion-2016-2026.pdf` | Disponible; documento diferenciado |

## Preview temporal de GitHub Pages

La página se descubre desde la navegación institucional durante la exportación con `scripts/update-github-preview.ps1`; el exportador ya admite `.pdf` y transforma las URLs locales a la base pública de la preview.

Los nueve PDF y la Memoria suman aproximadamente 155,2 MB. Por ese motivo no se regeneró ni se versionó automáticamente `github-preview/` en esta tarea: hacerlo incorporaría esos binarios al árbol de preview. Antes de publicar esa preview, decidir si los PDF se versionarán allí o si se servirán desde el alojamiento de medios definitivo. La página HTML y sus tarjetas sí se pueden regenerar con el script cuando esa decisión esté aprobada.
