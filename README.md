# About
Rank decay script for ELO-based ranking plugins.

This originated from: https://github.com/supimfuzzy/csgo-multi-1v1

Plenty of available customization for your standard SourceMod-based plugin ranking system (rankme, multi-1v1, etc).

# Instructions
1. Clone the repo
2. `cp antisquatter.config.example.php antisquatter.config.php`
3. Enter your configuration values into the new config file
4. Set a cronjob to run daily and voila, you have a nice and automated way of decaying ranks.

# Changelog (from original script)
- Configurable column names
- Configurable start score
- Configurable decay start time
- Added reverse decay with configurable multiplier
