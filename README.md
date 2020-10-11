# About
Rank decay script for ELO-based SourceMod ranking plugins. Plenty of available customization, and works out of the box with [Multi-1v1 Arenas](https://github.com/splewis/csgo-multi-1v1) and [RankMe](https://github.com/rogeraabbccdd/Kento-Rankme).

This originated from: https://github.com/supimfuzzy/csgo-multi-1v1

# Instructions
1. Clone the repo to desired folder `git clone https://github.com/Sneaks-Community/rank-decay-script.git .`
2. Copy example config file to antisquatter.config.php `cp antisquatter.config.example.php antisquatter.config.php`
3. Enter your configuration values into the new config file
4. Set a cronjob to run daily and voila, you have a nice and automated way of decaying ranks. `wget https://yourdomain.tld/antisquatter.php?p=anti_squatter_pass && rm -rf antisquatter.php*`

# Changelog (from original script)
- Configurable column names
- Configurable start score
- Configurable decay start time
- Added reverse decay with configurable multiplier
- Ability to reset players back to default rating/score
