import constant
import sys
import time
import random
import telepot
import emoji
import json
from telepot.loop import MessageLoop
from telepot.namedtuple import InlineKeyboardMarkup, InlineKeyboardButton

def on_chat_message(msg):
    content_type, chat_type, chat_id = telepot.glance(msg)
    if content_type == 'text':
        reply(chat_id, msg['text'])

def ciclaParoleChiave(array):
    string = ''
    for x in array:
        string += '- '+ x + '\n'
    return string

def printKeyWords(id):
    list = 'SUSHI?\n'
    list += ciclaParoleChiave(constant.SUSHI)
    list += '\nAFORISMI CELEBRI\n'
    list += ciclaParoleChiave(constant.ZUC)
    list += '\nIPNOROSPO\n'
    list += ciclaParoleChiave(constant.IPNOROSPO)
    list += '\nTAKE A BREAK?\n'
    list += ciclaParoleChiave(constant.PAUSA)
    list += '\nCAPITAN MUTANDA\n'
    list += '- leo\n'
    list += '\nCORDIALITA\n'
    list += ciclaParoleChiave(constant.GRAZIE)
    list += '\nSPORT NAZIONALE\n'
    list += '- votazione\n'
    bot.sendMessage(id, list)

def on_callback_query(msg):
    query_id, chat_id, query_data = telepot.glance(msg, flavor='callback_query')
    chat_group_id = bot.getUpdates()[0]['callback_query']['message']['chat']['id']
    if query_data == '1':
        bot.sendMessage(chat_group_id, emoji.emojize(':poop:', use_aliases=True))
    elif query_data == '2':
        bot.sendMessage(chat_group_id, emoji.emojize(':-1:', use_aliases=True))
    elif query_data == '3':
        bot.sendMessage(chat_group_id, emoji.emojize(':neutral_face:', use_aliases=True))
    elif query_data == '4':
        bot.sendMessage(chat_group_id, emoji.emojize(':+1:', use_aliases=True))
    elif query_data == '5':
        bot.sendMessage(chat_group_id, emoji.emojize(':tada:', use_aliases=True))

def reply(id, msg):
    msg = msg.lower()
    if msg == '/keyword':
        printKeyWords(id)
    elif any(msg in s for s in constant.SUSHI):
        bot.sendPhoto(id, open('img/' + random.choice(constant.SUSHI_IMAGES) +'.png', 'rb'))
    elif any(msg in s for s in constant.ZUC):
        bot.sendMessage(id, random.choice(constant.ZUC_MESSAGE))
    elif any(msg in s for s in constant.IPNOROSPO):
        bot.sendDocument(id, open('img/ipnorospo.gif', 'rb'))
    elif any(msg in s for s in constant.PAUSA):
        bot.sendDocument(id, open('img/' + random.choice(constant.PAUSA_IMAGES) +'.gif', 'rb'))
    elif msg == 'leo':
        bot.sendPhoto(id, open('img/capitanmutanda.jpg', 'rb'))
    elif any(msg in s for s in constant.GRAZIE):
        for i in range(5):
            bot.sendMessage(id, random.choice(['Grazie', 'Grazie mille']))
            time.sleep(0.5)
    elif msg == 'votazione':
        keyboard = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text='1', callback_data='1')],
            [InlineKeyboardButton(text='2', callback_data='2')],
            [InlineKeyboardButton(text='3', callback_data='3')],
            [InlineKeyboardButton(text='4', callback_data='4')],
            [InlineKeyboardButton(text='5', callback_data='5')],
        ])
        bot.sendMessage(id, 'Esprimi il tuo parere', reply_markup=keyboard)
    #else:
        #bot.sendMessage(id, emoji.emojize(':japanese_ogre:', use_aliases=True))

# PERILBOT -------------------------------------------------------------------------------------------------------------

#set proxy
telepot.api.set_proxy('http://192.168.8.8:3128')

bot = telepot.Bot(constant.TOKEN)
MessageLoop(bot, {'chat': on_chat_message,'callback_query': on_callback_query}).run_as_thread()

print ('Listening ...')

# Keep the program running.
while 1:
    time.sleep(10)
