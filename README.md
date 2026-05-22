# Junior PHP Test Task
## Частина А: MySQL Optimization
**Що було виправлено:**
1. URL винесено в окрему таблицю `urls`, щоб уникнути дублювання рядків.
2. `created_at` змінено з `VARCHAR` на `DATETIME` для коректного пошуку за діапазоном. `device` переведено в `ENUM`.
3. Додано композитний індекс `(country, created_at, url_id, user_id)`, який буде краще звіту.
