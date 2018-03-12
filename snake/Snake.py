#!/usr/bin/env python

import os, pygame, sys, MySQLdb, time
from pygame.locals import *
import random

pygame.init()

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

def get_scores():
    high_scores =[]

    try:
        dbSnake = MySQLdb.connect(host="127.0.0.1", user="root", passwd="alessandro", db="snake", connect_timeout=10)
    except:
        result = Display_text('No connection - local scores only',360,175,15,(255,0,0))
        all.add(result)
        repaint_screen()
        pygame.time.delay(1000)
        glob_scores = 0
        Filepath = os.path.join('data', 'scores.dat')
        if os.path.isfile(Filepath):
            FILE = open(Filepath,"r")
            text = FILE.readline()
            high_scores = text.split('"') 
            FILE.close()
        else:
            for i in range(1,11):
                high_scores.append(str(i))
                high_scores.append('0')
                high_scores.append('Cifa')
    else:
        result = Display_text('Connessione stabilita...',360,200,15,(255,255,255))
        all.add(result)
        repaint_screen()
        pygame.time.delay(1000)
        glob_scores = 1
        cursor = dbSnake.cursor()
        cursor.execute("SELECT * FROM scores ORDER BY score DESC, record_date")
        score_data = cursor.fetchall()
        rows = len (score_data)
        for i in range (0,rows):
            high_scores.append(str(i+1))
            high_scores.append(str(score_data[i][0]))
            high_scores.append(score_data[i][1])
    
        dbSnake.close()
         
    all.remove(result)
    return high_scores, glob_scores

def repaint_screen():
    all.clear(screen, background) 
    dirty = all.draw(screen)
    pygame.display.update(dirty)
    
def save_scores(high_scores, score, name='', online=0):
    Filepath = os.path.join('data', 'scores.dat')
    # Create a file object:
    # in "write" mode
    FILE = open(Filepath,"w")
    for i in range(0,30):
        if i <> 29:
            FILE.write(high_scores[i]+'"')
        else:
            FILE.write(high_scores[i])
    FILE.close()
    
    if online:
        try:
            dbSnake = MySQLdb.connect(host="127.0.0.1", user="root", passwd="alessandro", db="snake", connect_timeout=10)
        except:
            pass
        else:
            cursor = dbSnake.cursor()
            cursor.execute("SELECT * FROM scores WHERE score = %s",(score))
            scores_data = cursor.fetchall()
            position = len(scores_data)
            cursor.execute("INSERT INTO scores (score, name, record_date) VALUES (%s, %s, %s)", (score, name, position))
            dbSnake.commit()
            cursor.execute("SELECT * FROM scores ORDER BY score DESC, record_date")
            scores_data = cursor.fetchall()
            rows = len(scores_data)
            if rows > 10:
                bottom = scores_data[9][0]
                cursor.execute("DELETE FROM scores WHERE score < %s",(bottom,))
                dbSnake.commit()
            dbSnake.close()
        
        
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
        self.font = pygame.font.Font("freesansbold.ttf", 20)
        #self.font.set_italic(1)
        self.color = Color('white')
        self.lastscore = -1
        self.update()
        self.rect = self.image.get_rect().move(450, 5)

    def update(self):
        if score != self.lastscore:
            self.lastscore = score
            msg = "Punteggio: %d" % score
            self.image = self.font.render(msg, 0, self.color)

class Text(pygame.sprite.Sprite):
    def __init__(self,status,bonus = 0):
        pygame.sprite.Sprite.__init__(self)
        if status == 1:
            text = "Ouch!!Attento al muro!"
        elif status == 0:
            text = "Ouch!!Ti sei mangiato da solo!!"
        elif status == 2:
            text = "Pausa"
        elif status == 3:
            text = "Bonus %d" % bonus
        elif status == 4:
            text = "Vuoi giocare ancora? (y/n)"
        elif status == 5:
            text = "GAME OVER"
        self.font = pygame.font.Font("freesansbold.ttf", 28)
        self.image = self.font.render(text, 1, (Color('white')))
        self.rect = self.image.get_rect(centerx = background.get_width()/2,centery = background.get_height()/2)

class Display_text(pygame.sprite.Sprite):
    def __init__(self,text,position_top,position_left,size,colour):
        pygame.sprite.Sprite.__init__(self)
        self.font = pygame.font.Font("freesansbold.ttf", size)
        self.image = self.font.render(text,1,colour)
        self.rect = self.image.get_rect(left = position_left,top = position_top)

    def update(self,text,underscore,colour):
        text = text + underscore
        self.image = self.font.render(text,1,colour)

class Main_Image(pygame.sprite.Sprite):
    images=[]
    def __init__(self):
        pygame.sprite.Sprite.__init__(self) #call Sprite initializer
        self.image = self.images[0]
        self.rect = (30,30,540,540)
        
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
    pygame.display.set_caption('SNAKE')
    background = load_image('background2.jpg')
    screen.blit(background,(0,0))
    pygame.display.flip()

    #load pictures for sprites
    eat_sound = load_sound('yipee.wav')
    crash_sound = load_sound('foghorn.wav')
    bonus_sound = load_sound('hey.wav')
    Centipede.images = load_images('head.gif','head.gif','explosion1.gif')
    Food.images = [load_image('apple.gif',-1)]
    Body.images = [load_image('body.gif',-1)]
    Bonus.images = [load_image('mouse.gif',-1)]
    Main_Image.images = [load_image('home.jpg')]
    
    pygame.mouse.set_visible(0)
    
    width = screen.get_width() - 50
    height = screen.get_height() - 50

    #start screen
    if start == 0:
        start = 1
        all.add(Main_Image())
        all.add(Display_text('PYTHON SNAKE',240,110,50,(Color('white'))))
        all.add(Display_text('Per iniziare a giocare premere un tasto qualsiasi...',300,100,18,(Color('white'))))
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
                
        # game over
        if snake_alive == 0:
            pygame.time.delay(2000)
            crash_text.kill()
            game_over = Text(5)
            promt = Display_text('(Premi un tasto per continuare)',350,200,15,(255,255,255))
            all.add(game_over,promt)
            repaint_screen()
            pygame.event.clear()
            while 1:
                event = pygame.event.wait()
                if event.type == QUIT:
                    sys.exit()
                if event.type == KEYDOWN:
                    break
            all.remove(game_over, promt, centipede, food)
            for body in bodies:
                body.kill()
            if bonus.alive():
                all.remove(bonus)
            # create high scores
            info = Display_text('Connessione al db in corso..',300,160,18,(255,255,255))
            all.add(info)
            repaint_screen()
            pygame.time.delay(1000)
            high_scores, glob_scores = get_scores()
            all.remove(info)
            
            place = 0
            if score > int(high_scores[28]):
                for i in range(25,-3,-3):
                    if i < 0:
                        high_scores[1] = str(score)
                        high_scores[2] = "_"
                        place = 3
                        break
                    if score > int(high_scores[i]):
                        high_scores[i+3] = high_scores[i]
                        high_scores[i+4] = high_scores[i+1]
                    else:
                        high_scores[i+3] = str(score)
                        high_scores[i+4] = "_"
                        place = i+5
                        break
            score_text = []
            if glob_scores:
                score_text.append(Display_text('HIGH SCORES (DB TABLE)',80,110,28,(255,255,255)))
            else:
                score_text.append(Display_text('HIGH SCORES (LOCAL  TABLE)',80,110,28,(255,255,255)))
            place_pos = 190
            for i in range (0,30,3):
                if place - 3 == i:
                    colour = (255,0,0)
                else:
                    colour = (0,0,255)
                if i == 27:
                    place_pos = 180
                point = high_scores[i+1].find('.')
                if point > 0:
                    high_scores[i+1] = high_scores[i+1] [0:point]
                score_text.append(Display_text(high_scores[i],int(high_scores[i])*35+100,place_pos,20,colour))
                score_text.append(Display_text(high_scores[i+1],int(high_scores[i])*35+100,300-(len(high_scores[i+1])*12),20,colour))
                score_text.append(Display_text(high_scores[i+2],int(high_scores[i])*35+100,340,20,colour))
            if place > 0:
                score_text.append(Display_text('(CONGRATULAZIONI!! Inserisci il tuo nome.)',500,180,15,(255,255,255)))
            else:
                score_text.append(Display_text('(Premi un tasto per continuare)',500,205,15,(255,255,255)))
            all.add(score_text)
            repaint_screen()
            line = pygame.draw.line(screen,(0,0,255),(110,120),(520,120),3)
            pygame.display.update(line)
            
            if place > 0:
                current_string = ''
                while 1:
                    event = pygame.event.wait()
                    if event.type == QUIT:
                        sys.exit()
                    if event.type != KEYDOWN:
                        continue
                    if event.key == K_BACKSPACE:
                        current_string = current_string[:-1]
                    elif event.key == K_RETURN:
                        if len(current_string) == 0:
                            current_string = 'Player 1'
                        score_text[place].update(current_string,'',(255,255,255))
                        score_text[31].update('SALVATAGGIO IN CORSO..','',(255,255,255))
                        repaint_screen()
                        line = pygame.draw.line(screen,(0,0,255),(110,120),(520,120),3)
                        pygame.display.update(line) 
                        break
                    elif event.unicode:
                        if len(current_string) <= 15:
                            if event.unicode <> '"':
                                current_string += event.unicode
                    score_text[place].update(current_string,'_',(255,0,0))
                    repaint_screen()
                    line = pygame.draw.line(screen,(0,0,255),(110,120),(520,120),3)
                    pygame.display.update(line) 
                    
                high_scores[place-1] = current_string
                save_scores(high_scores, score, current_string, glob_scores)
            
                score_text[31].update(' SALVATAGGIO ESEGUITO! Premi un tasto per continuare. ','',(255,255,255))
                repaint_screen()
                line = pygame.draw.line(screen,(0,0,255),(110,120),(520,120),3)
                pygame.display.update(line)
            else:
                # update your local scores with global
                if glob_scores:
                    save_scores(high_scores, score)
            
            while 1:
                event = pygame.event.wait()
                if event.type == QUIT:
                    sys.exit()
                if event.type == KEYDOWN:
                    break
            all.remove(score_text)
            all.add(Main_Image())
            all.add(Display_text('Vuoi giocare ancora? (y/n)',250,230,20,(Color('white'))))
            repaint_screen()
                    
        if begin == 1:
            begin = 0
            pygame.time.delay(1000)

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
            


