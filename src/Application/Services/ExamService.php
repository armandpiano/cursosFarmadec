<?php

namespace Farmadec\Application\Services;

use Farmadec\Infrastructure\Persistence\Repositories\{
    MySQLExamRepository,
    MySQLProgressRepository
};

/**
 * Servicio de Exámenes y Evaluaciones
 */
class ExamService
{
    /** @var MySQLExamRepository */
    private $examRepository;
    
    /** @var MySQLProgressRepository */
    private $progressRepository;
    
    public function __construct()
    {
        $this->examRepository = new MySQLExamRepository();
        $this->progressRepository = new MySQLProgressRepository();
    }
    
    /**
     * Obtener examen de un módulo
     */
    public function getExamByModuleId($module_id)
    {
        return $this->examRepository->findByModuleId($module_id);
    }
    
    /**
     * Evaluar respuestas del examen
     */
    public function evaluateExam($user_id, $exam_id, $answers)
    {
        $exam = $this->examRepository->findById($exam_id);
        
        if (!$exam) {
            return ['success' => false, 'message' => 'Examen no encontrado'];
        }
        
        $normalizedAnswers = [];
        foreach ($answers as $key => $value) {
            if (is_string($key) && preg_match('/^q(\d+)/', $key, $matches)) {
                $normalizedAnswers[(int)$matches[1]] = $value;
            } else {
                $normalizedAnswers[(int)$key] = $value;
            }
        }

        $questions = $exam->getQuestions();
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        
        foreach ($questions as $question) {
            $question_id = $question->getId();
            
            if (!isset($normalizedAnswers[$question_id])) {
                continue;
            }

            $userAnswer = $normalizedAnswers[$question_id];
            $isCorrect = false;
            
            if ($question->getType() === 'true_false' || $question->getType() === 'single_choice') {
                $isCorrect = $this->checkSingleAnswer($question, $userAnswer);
            } elseif ($question->getType() === 'multiple_choice') {
                $isCorrect = $this->checkMultipleAnswers($question, $userAnswer);
            }
            
            if ($isCorrect) {
                $correctAnswers++;
            }
        }
        
        $score = $totalQuestions > 0 ? (int)(($correctAnswers / $totalQuestions) * 100) : 0;
        $passed = $score >= $exam->getPassScore();

        $this->progressRepository->saveAttempt($user_id, $exam_id, $score, $passed, $normalizedAnswers);
        
        return [
            'success' => true,
            'score' => $score,
            'passed' => $passed,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'pass_score' => $exam->getPassScore()
        ];
    }
    
    /**
     * Verificar respuesta simple (true/false o single choice)
     */
    private function checkSingleAnswer($question, $userAnswer)
    {
        $options = $question->getOptions();
        
        foreach ($options as $option) {
            if ((int)$option->getId() === (int)$userAnswer && $option->isCorrect()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verificar respuestas múltiples
     */
    private function checkMultipleAnswers($question, $userAnswers)
    {
        if (!is_array($userAnswers)) {
            $userAnswers = [$userAnswers];
        }
        
        $options = $question->getOptions();
        $correctIds = [];
        
        foreach ($options as $option) {
            if ($option->isCorrect()) {
                $correctIds[] = (int)$option->getId();
            }
        }
        
        $userIds = array_map('intval', $userAnswers);
        sort($correctIds);
        sort($userIds);
        
        return $correctIds === $userIds;
    }
    
    /**
     * Obtener mejor intento del usuario en un examen
     */
    public function getBestAttempt($user_id, $exam_id)
    {
        return $this->progressRepository->getBestAttempt($user_id, $exam_id);
    }
    
    /**
     * Verificar si el usuario aprobó el examen
     */
    public function hasPassedExam($user_id, $exam_id)
    {
        $attempt = $this->getBestAttempt($user_id, $exam_id);
        return $attempt && $attempt['passed'];
    }
}
