<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\{Exam, Question, Option};
use PDO;

/**
 * Repositorio MySQL para Exams
 */
class MySQLExamRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    public function findByModuleId($module_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM exams WHERE module_id = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$module_id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        $exam = $this->hydrate($data);
        $exam->setQuestions($this->loadQuestions($exam->getId()));
        
        return $exam;
    }
    
    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM exams WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        $exam = $this->hydrate($data);
        $exam->setQuestions($this->loadQuestions($exam->getId()));
        
        return $exam;
    }
    
    public function create(Exam $exam)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO exams (module_id, pass_score, is_active) VALUES (?, ?, ?)'
        );
        
        $stmt->execute([
            $exam->getModuleId(),
            $exam->getPassScore(),
            $exam->isActive() ? 1 : 0
        ]);
        
        $exam->setId((int)$this->db->lastInsertId());
        return $exam;
    }
    
    public function update(Exam $exam)
    {
        $stmt = $this->db->prepare(
            'UPDATE exams SET module_id = ?, pass_score = ?, is_active = ? WHERE id = ?'
        );
        
        $stmt->execute([
            $exam->getModuleId(),
            $exam->getPassScore(),
            $exam->isActive() ? 1 : 0,
            $exam->getId()
        ]);
        
        return $exam;
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM exams WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    private function loadQuestions($exam_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM questions WHERE exam_id = ?');
        $stmt->execute([$exam_id]);
        $questions = [];
        
        while ($data = $stmt->fetch()) {
            $question = new Question(
                (int)$data['exam_id'],
                $data['text'],
                $data['type']
            );
            $question->setId((int)$data['id']);
            $question->setOptions($this->loadOptions($question->getId()));
            
            $questions[] = $question;
        }
        
        return $questions;
    }
    
    private function loadOptions($question_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM options WHERE question_id = ?');
        $stmt->execute([$question_id]);
        $options = [];
        
        while ($data = $stmt->fetch()) {
            $option = new Option(
                (int)$data['question_id'],
                $data['text'],
                (bool)$data['is_correct']
            );
            $option->setId((int)$data['id']);
            $options[] = $option;
        }
        
        return $options;
    }
    
    private function hydrate($data)
    {
        $exam = new Exam(
            (int)$data['module_id'],
            (int)$data['pass_score'],
            (bool)$data['is_active']
        );
        $exam->setId((int)$data['id']);
        
        return $exam;
    }
}
