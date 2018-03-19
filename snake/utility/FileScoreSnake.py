import os

def findScores(conf):
    high_scores = []

    file_score = os.getcwd() + conf['path']
    with open(file_score) as f:
        lines = f.read().splitlines()
        for line in lines:
            pos,name,score = line.split(",")
            high_scores.append(score)

    return high_scores

def saveScore(conf, current_score, name):

    file_score = os.getcwd() + conf['path']
    with open(file_score) as f:
        lines = f.read().splitlines()
