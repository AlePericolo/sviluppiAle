
import os, pygame, sys, MySQLdb, datetime
from utility import inputbox, ReadConf, DatabaseSnake, FileScoreSnake, functions
from pygame.locals import *

import random

pygame.init()

#-------------------------------------------------LOAD IMAGES-----------------------------------------------------------
def load_image(name, colorkey=None):
    "loads an image, prepares it for play"
    fullname = os.path.join('data/img', name)
    try:
        surface = pygame.image.load(fullname)
    except pygame.error:
        raise SystemExit, 'Could not load image "%s" %s'%(file, pygame.get_error())
    if colorkey is not None:
        if colorkey is -1:
            colorkey = surface.get_at((0,0))
        surface.set_colorkey(colorkey, RLEACCEL)
    return surface.convert()

def load_images(*files):
    imgs = []
    for file in files:
        imgs.append(load_image(file, -1))
    return imgs

def repaint_screen():
    all.clear(screen, background)
    dirty = all.draw(screen)
    pygame.display.update(dirty)

#---------------------------------------------------LOAD SOUND----------------------------------------------------------
def load_sound(name):
    class NoneSound:
        def play(self): pass
    if not pygame.mixer or not pygame.mixer.get_init():
        return NoneSound()
    fullname = os.path.join('data/sound', name)
    try:
        sound = pygame.mixer.Sound(fullname)
    except pygame.error, message:
        print 'Cannot load sound:', fullname
        raise SystemExit, message
    return sound

#----------------------------------------------------INIT SNAKE---------------------------------------------------------
class Centipede(pygame.sprite.Sprite):
    images = []

    def __init__(self):
        pygame.sprite.Sprite.__init__(self) #call Sprite initializer
        self.image = self.images[0]
        self.rect = self.image.get_rect()
        self.rect[0] = 290
        self.rect[1] = 290
        self.move = [0,-step]
        self.update_call = 0
        self.img_index = 0

    def update(self):
        self.update_call += 1
        pygame.event.pump()
        pos = [0,0,0,0]
        if self.rect[0]%20 == 10 and self.rect[1]%20 == 10:
            pos = [pygame.key.get_pressed()[K_DOWN],pygame.key.get_pressed()[K_UP],\
                 pygame.key.get_pressed()[K_LEFT],pygame.key.get_pressed()[K_RIGHT]]
        if sum(pos)==1:
            self.move[1] = (pos[0]-pos[1])*step
            self.move[0] = (pos[3]-pos[2])*step
#        print self.rect
        # mouth open or shut
        if self.update_call == 5: 
            self.update_call =0
            if self.img_index:
                self.img_index = 0
            else:
                self.img_index = 1

        # makes the head face in the direction of movement 
        if self.move[0] == -step:
            self.image = self.images[self.img_index]
        elif self.move[0] == step:
            self.image = pygame.transform.flip(self.images[self.img_index],1,0)
        elif self.move[1] == step:
            self.image = pygame.transform.rotate(self.images[self.img_index],90)
        else:
            self.image = pygame.transform.rotate(self.images[self.img_index],-90)
            
        newpos = self.rect.move((self.move))
        self.rect = newpos

    def outside(self,x,y):
        if self.rect[0] < 30 or self.rect[0] > x or self.rect[1] < 30 or self.rect[1] > y:
            self.end()
            return True
        else:
            return False

    def end (self):
        self.image = self.images[2]
        self.rect = self.rect.move(-20,-20)
        
    def position(self):
        return self.rect[0], self.rect[1]

class Body(pygame.sprite.Sprite):
    images = []
 
    def __init__(self, start):
        pygame.sprite.Sprite.__init__(self, self.containers) #call Sprite initializer
        self.image = self.images[0]
        self.rect = self.image.get_rect()
        self.moves = []
        self.rect[0] = 290
        self.rect[1] = start
        for i in range (0,3):
            self.moves.append((290,start+(i*-step)))
     
    def move(self,xy):
        self.rect[0] = xy[0]
        self.rect[1] = xy[1]
        self.moves.append(xy)
        del self.moves[0]
        
class Food(pygame.sprite.Sprite):
    images = []
    def __init__(self):
        pygame.sprite.Sprite.__init__(self) #call Sprite initializer
        self.image = self.images[0]
        self.rect = self.image.get_rect()
        self.rect[0] = (random.randrange(1,28,1)*20)+16
        self.rect[1] = (random.randrange(1,28,1)*20)+16
        
class Bonus(pygame.sprite.Sprite):
    images = []
    def __init__(self):
        pygame.sprite.Sprite.__init__(self) #call Sprite initializer
        self.image = self.images[0]
        self.rect = self.image.get_rect()
        self.rect[0] = (random.randrange(1,28,1)*20)+16
        self.rect[1] = (random.randrange(1,28,1)*20)+16
        
class Score(pygame.sprite.Sprite):
    def __init__(self):
        pygame.sprite.Sprite.__init__(self)
        self.font = pygame.font.Font("data/font/8bitfont.ttf", 25)
        #self.font.set_italic(1)
        self.color = Color('white')
        self.lastscore = -1
        self.update()
        self.rect = self.image.get_rect().move(480, 5)

    def update(self):
        if score != self.lastscore:
            self.lastscore = score
            msg = "Score: %d" % score
            self.image = self.font.render(msg, 0, self.color)

class Text(pygame.sprite.Sprite):
    def __init__(self,status,bonus = 0):
        pygame.sprite.Sprite.__init__(self)
        if status == 1:
            text = "Il laser ti ha fritto!"
        elif status == 0:
            text = "Ti sei mangiato da solo!"
        elif status == 2:
            text = "Pausa"
        elif status == 3:
            text = "Bonus %d" % bonus
        elif status == 4:
            text = "Vuoi giocare ancora? (y/n)"
        elif status == 5:
            text = "GAME OVER"
        self.font = pygame.font.Font("data/font/8bitfont.ttf", 50)
        self.image = self.font.render(text, 1, (Color(255, 242, 5)))
        self.rect = self.image.get_rect(centerx = background.get_width()/2,centery = background.get_height()/2)

class Display_text(pygame.sprite.Sprite):
    def __init__(self,text,position_top,position_left,size,colour):
        pygame.sprite.Sprite.__init__(self)
        self.font = pygame.font.Font("data/font/8bitfont.ttf", size)
        self.image = self.font.render(text,1,colour)
        self.rect = self.image.get_rect(left = position_left,top = position_top)

    def update(self,text,underscore,colour):
        text = text + underscore
        self.image = self.font.render(text, 1, colour)

class Main_Image(pygame.sprite.Sprite):
    images=[]
    def __init__(self):
        pygame.sprite.Sprite.__init__(self) #call Sprite initializer
        self.image = self.images[0]
        self.rect = (30,30,540,540)

#=====================================================START GAME========================================================
def main(start):
    
    #initialize variables
    global screen, background, step, score, all, bodies
    begin = 1
    step = 10
    score = 0
    snake_alive = 1
    headmoves = [(290,300),(290,290)]
    clock = pygame.time.Clock()
    bodies = []
    all = pygame.sprite.OrderedUpdates()
    bonus_status = 0
    bonus_prob = 1000
    bonus_time = 0
    text_time = 0
    crash_text = pygame.sprite.Sprite()
    bonus_text = pygame.sprite.Sprite()
    bonus = pygame.sprite.Sprite()

    #get main frame
    os.environ['SDL_VIDEO_CENTERED'] = 'anything'
    fullname = os.path.join('data/img', 'Snake.gif')
    pygame.display.set_icon(pygame.image.load(fullname))
    screen = pygame.display.set_mode((600,600))
    pygame.display.set_caption('SNAKE - Peril')
    background = load_image('background.png')
    screen.blit(background,(0,0))
    pygame.display.flip()

    #load pictures for sprites
    eat_sound = load_sound('yipee.wav')
    crash_sound = load_sound('foghorn.wav')
    bonus_sound = load_sound('hey.wav')
    Centipede.images = load_images('head.gif','head.gif','explosion1.gif')
    Food.images = [load_image('apple.gif',-1)]
    Body.images = [load_image('body.gif',-1)]
    Bonus.images = [load_image('ufo.gif',-1)]
    Main_Image.images = [load_image('home.png')]
    
    pygame.mouse.set_visible(0)
    
    width = screen.get_width() - 50
    height = screen.get_height() - 50

    #start screen
    if start == 0:
        start = 1
        all.add(Main_Image())
        all.add(Display_text('Per iniziare a giocare premere un tasto qualsiasi',550,35,25,(Color(255, 138, 35))))
        repaint_screen()
        while 1:
            event = pygame.event.wait()
            if event.type == QUIT:
                sys.exit()
            if event.type == KEYDOWN:
                break   
        all.empty()
    
    #create head...
    centipede = Centipede()
    centirect = centipede.rect
    Body.containers = all
    
    #...and body...
    for i in range (3):
        bodies.append(Body(330+(i*20)))
    #...and some food
    food = Food()
    
    # make sure food isn't in same place as snake
    while 1:
        if centirect.colliderect(food.rect) or pygame.sprite.spritecollide(food,bodies,0) != []: 
            food.kill()
            food = Food()
        else:
            break
        
    all.add(food, centipede)
    
    # initialize score
    if pygame.font:
        score_instance = Score()
        all.add(score_instance)
        
    pygame.time.delay(400)
    pygame.event.clear()
    
    # main game loop
    while snake_alive:
        bonus_time -= 1
        text_time -= 1
        clock.tick(25)
        
        centirect = centipede.rect
        
        #handles pause and exit
        for event in pygame.event.get():
            if event.type == QUIT:
                sys.exit()
            elif event.type == KEYDOWN and event.key == K_ESCAPE:
                sys.exit()
            elif event.type == KEYDOWN and event.key == K_p:
                pause = 1
                if pygame.font:
                    pause_text=Text(2)
                    all.add(pause_text)
                all.clear(screen, background) 
                dirty = all.draw(screen)
                pygame.display.update(dirty)
                pygame.event.clear()
                while pause:
                    event = pygame.event.wait()
                    if event.type == QUIT:
                        sys.exit()
                    if event.type == KEYDOWN and event.key == K_p:
                        pause = 0
                        pause_text.kill()
                
        all.update()
        
        # make body move
        lastmove = headmoves[0]        
        for body in bodies:
            body.move(lastmove)
            lastmove = body.moves[0]
        
        # update moves of head
        headmoves.append(centipede.position())
        del headmoves[0]
        
        # detects collision with the wall
        if centipede.outside(width,height):
            snake_alive = 0
            crash_sound.play()
            #re-create head to make sure it's last sprite in all
            #and nothing is drawn over it
            all.remove(centipede)
            all.remove(score_instance)
            all.add(centipede)
            all.add(score_instance)
            centipede.end()
            if bonus_text.alive():
                bonus_text.kill()
            if pygame.font:
                crash_text = Text(1)
                all.add(crash_text)

        # collision between head and body
        if snake_alive != 0:
            smallcenti = centipede.rect.inflate(-15,-15)  
            for body in bodies:
                smallbody = body.rect.inflate(-15,-15)
                if smallbody.colliderect(smallcenti):
                    snake_alive = 0
                    crash_sound.play()
                    all.remove(centipede)
                    all.remove(score_instance)
                    all.add(centipede)
                    all.add(score_instance)
                    centipede.end()
                    if bonus_text.alive():
                        bonus_text.kill()
                    if pygame.font:
                        crash_text = Text(0)
                        all.add(crash_text)

        # repaint before you make snake grow
        # otherwise new body will show in default position
        # before being appended to snake                 
        repaint_screen()
                
        # check if food has been eaten
        # creates new food
        # makes body grow
        if snake_alive != 0 and centirect.colliderect(food.rect):
            food.kill()
            score = score + 1
            bonus_prob = bonus_prob - 1
            eat_sound.play()
            food = Food()
            bodies.append(Body(304))
            while 1:
                if bonus.alive():
                    if centirect.colliderect(food.rect) or pygame.sprite.spritecollide(food,bodies,0) != [] or bonusrect.colliderect(food.rect):
                        food.kill()
                        food = Food()
                    else:
                        break
                else:
                    if centirect.colliderect(food.rect) or pygame.sprite.spritecollide(food,bodies,0) != []:
                        food.kill()
                        food = Food()
                    else:
                        break
            all.add(food)

        # display new bonus(only one at a time) 
        if bonus_status == 0 and random.randrange(1,1000,1) > bonus_prob:
            bonus_status = 1
            bonus = Bonus()
            bonusrect = bonus.rect
            bonus_time = random.randrange(40,100,1)
            while 1:
                if bonusrect.colliderect(food.rect) or bonusrect.colliderect(centipede.rect) or pygame.sprite.spritecollide(bonus,bodies, 0) != []:
                    bonus.kill()
                    bonus = Bonus()
                    bonusrect = bonus.rect
                else:
                    break
            all.add(bonus)
        
        # kill bonus when time is up
        if bonus_time == 0:
            bonus.kill()
            bonus_status = 0
            
        # kill bonus text
        if text_time == 0:
            bonus_text.kill()

        # check if bonus has been eaten
        if bonus.alive() and snake_alive != 0:
            if bonusrect.colliderect(centipede.rect):
                bonus.kill()
                bonus_status = 0
                bonus_prob = 1000
                if bonus_text.alive():
                    bonus_text.kill()
                bonus_points = round(bonus_time/5+2)
                score = score + bonus_points
                bonus_sound.play()
                bonus_text = Text(3,bonus_points)
                text_time = 25
                all.add(bonus_text)
                bodies.append(Body(304))
                bonus_time = 0
                
        # -----------------------------------------------GAME OVER------------------------------------------------------
        if snake_alive == 0:

            while 1:
                event = pygame.event.wait()
                if event.type == QUIT:
                    sys.exit()
                if event.type == KEYDOWN:
                    break

            #remove game object
            all.remove(crash_text, centipede, bodies, food, bonus)

            #SAVE
            save_scores(score) #check score > lower score in highscore and save

            #visualizzo punteggi----------------------------------------------------------
            repaint_screen()
            background = load_image('highscore.jpg')
            screen.blit(background, (0, 0))
            pygame.display.flip()

            c = ReadConf.ReadConf()
            db = DatabaseSnake.DatabaseSnake(c.database)

            top = 230
            for el in db.getHighScoresList():
                playerPos = str(el.get('pos'))
                playerName = str(el.get('name'))
                playerScore = str(el.get('score')).zfill(6)
                left = 110
                colorText = functions.findPosition(playerPos)
                all.add(Display_text(playerPos, top, left, 40, colorText))
                all.add(Display_text(playerName.upper(), top, left +50, 40, colorText))
                all.add(Display_text(playerScore, top, left +260, 40, colorText))
                top += 30
            # visualizzo punteggi----------------------------------------------------------

            all.add(Display_text('Vuoi giocare ancora?  (y/n)',470, 130, 30,(255, 242, 5)))
            repaint_screen()

        if begin == 1:
            begin = 0
            pygame.time.delay(1000)


#--------------------------------------------------GESTIONE PUNTEGGI----------------------------------------------------

#try db connection
def check_db_connection(conf):
    try:
        dbSnake = MySQLdb.connect(conf['host'], conf['user'], conf['password'], conf['database'], connect_timeout=10)
    except:
        return 0
    else:
        dbSnake.close()
        return 1

def save_scores(current_score):

    c = ReadConf.ReadConf() #get configuration fronm file
    db_score = check_db_connection(c.database) #check connection [1/0]

    #get highscores
    if db_score:
        db = DatabaseSnake.DatabaseSnake(c.database)
        high_scores = db.findScores() #from db
    else:
        high_scores = FileScoreSnake.findScores(c.file) #from file

    min_score = min(high_scores) #get min_score from highscores

    #check current_score > min_score
    if current_score > min_score:
        nome = inputbox.ask(screen) #get player name
        #save score & name
        if db_score:
            db = DatabaseSnake.DatabaseSnake(c.database)
            db.saveScore(current_score, nome[:6], datetime.datetime.now()) #to db
            db.cleanScore() #clear db over 5 row
        else:
            FileScoreSnake.saveScore(c.file, current_score, nome[:6]) #to file


# start game when loaded first time              
if __name__ == '__main__': main(0)

# handles 'play again' situation
end = 1
while end:
    event = pygame.event.wait()
    if event.type == QUIT:
        end = 0
    if event.type != KEYDOWN:
        continue
    if event.key == K_ESCAPE:
        end = 0
    elif event.key == K_n:
        end = 0
    elif event.key == K_y:
        main(1)
            


