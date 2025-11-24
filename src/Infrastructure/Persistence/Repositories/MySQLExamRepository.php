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
        // Preferir el esquema exam_questions
        $stmt = $this->db->prepare('SELECT * FROM exam_questions WHERE exam_id = ? ORDER BY order_index ASC, id ASC');
        $stmt->execute([$exam_id]);
        $questions = [];

        $questionsData = $stmt->fetchAll();

        // Compatibilidad con esquema anterior
        if (empty($questionsData)) {
            $stmt = $this->db->prepare('SELECT * FROM questions WHERE exam_id = ?');
            $stmt->execute([$exam_id]);
            $questionsData = $stmt->fetchAll();
        }

        foreach ($questionsData as $data) {
            $question = new Question(
                (int)$data['exam_id'],
                $data['question_text'] ?? $data['text'],
                $data['question_type'] ?? $data['type']
            );

            $question->setId((int)$data['id']);
            $question->setOptions($this->loadOptions($question));

            $questions[] = $question;
        }

        return $questions;
    }

    private function loadOptions(Question $question)
    {
        $options = [];

        // Intentar cargar opciones embebidas en JSON (nuevo esquema)
        $stmt = $this->db->prepare('SELECT options FROM exam_questions WHERE id = ?');
        $stmt->execute([$question->getId()]);
        $row = $stmt->fetch();

        if ($row && !empty($row['options'])) {
            $jsonOptions = json_decode($row['options'], true);

            if (is_array($jsonOptions)) {
                foreach ($jsonOptions as $data) {
                    $option = new Option(
                        $question->getId(),
                        $data['text'] ?? '',
                        !empty($data['is_correct'])
                    );
                    if (isset($data['value'])) {
                        $option->setId((int)$data['value']);
                    }
                    $options[] = $option;
                }
            }
        }

        // Compatibilidad con tabla exam_options
        if (empty($options)) {
            $stmt = $this->db->prepare('SELECT * FROM exam_options WHERE question_id = ?');
            $stmt->execute([$question->getId()]);
            $dataOptions = $stmt->fetchAll();

            if (!empty($dataOptions)) {
                foreach ($dataOptions as $data) {
                    $option = new Option(
                        (int)$data['question_id'],
                        $data['text'],
                        (bool)$data['is_correct']
                    );
                    $option->setId((int)$data['id']);
                    $options[] = $option;
                }
            }
        }

        // Último recurso: opciones del esquema antiguo
        if (empty($options)) {
            $stmt = $this->db->prepare('SELECT * FROM options WHERE question_id = ?');
            $stmt->execute([$question->getId()]);

            while ($data = $stmt->fetch()) {
                $option = new Option(
                    (int)$data['question_id'],
                    $data['text'],
                    (bool)$data['is_correct']
                );
                $option->setId((int)$data['id']);
                $options[] = $option;
            }
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
