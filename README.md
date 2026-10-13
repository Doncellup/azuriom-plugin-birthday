# Birthday Rewards for Azuriom

Your players save their birthday once and receive a gift every year: website money and/or commands run on your game servers. Free plugin.

Author: **Doncellup** · Requires Azuriom 1.2+ (PHP 8.2+) · Languages: English, French

## Features

- The player picks the day and the month (no year is asked or stored), once: the date is then locked
- Yearly gift: website money and/or server commands (any game supported by Azuriom), with a custom name
- Claim period: the gift can be claimed on the birthday and during a configurable number of days after it
- Anti-abuse delays: the date must be saved, and the account created, a configurable number of days before the birthday to be entitled to that year's gift
- People born on February 29th celebrate on February 28th on common years
- Website notification on the birthday (on the first visit of the day), no scheduled task (CRON) needed
- Confetti on claim, optional entry in the player menu, optional welcome text (Markdown)
- Admin: upcoming birthdays, statistics, search, set or change a date (trusted, delays do not apply), reset a gift, delete a date, action logs, permission

## Server commands

Gift commands support `{player}` (replaced by Azuriom) and `{reward}` (the name of the gift).
Choose the servers where they run. Enable "The player must be online" to deliver the commands only when the player is connected (AzLink).
