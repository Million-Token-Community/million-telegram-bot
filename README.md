# Million Telegram Bot

## About

This is the source code for @million_token_bot on Telegram.  
The bot is made with PHP and with no dependencies on external libraries.
It runs for free on Heroku at https://million-telegram-bot.herokuapp.com/.

## Commands

The bot supports these commands:
- /price
- /holders
- /volume
- /gas
- /cat
- /lambo
- /top1000

Bot messages older than 1 minute are automatically deleted to declutter the Telegram chat.  
Repeating commands within a minute will also be deleted, so only the latest remains.

Commands can be tested at https://million-telegram-bot.herokuapp.com/test.php?message=<command>  
For example to test /price command: https://million-telegram-bot.herokuapp.com/test.php?message=/price

## Telegram API
Telegram's API is described here: https://core.telegram.org/bots/api