<?php

namespace Farmadec\Application\Services;

use Farmadec\Infrastructure\Persistence\Repositories\MySQLCertificateRepository;
use Farmadec\Domain\Entities\Certificate;

/**
 * Servicio de Certificados
 */
class CertificateService
{
    /** @var MySQLCertificateRepository */
    private $certificateRepository;
    
    /** @var MailService */
    private $mailService;
    
    public function __construct()
    {
        $this->certificateRepository = new MySQLCertificateRepository();
        $this->mailService = new MailService();
    }
    
    /**
     * Generar certificado para usuario y curso
     */
    public function generateCertificate($user, $course)
    {
        $existing = $this->certificateRepository->findByUserAndCourse($user['id'], $course->getId());
        
        if ($existing) {
            return $existing;
        }
        
        $code = $this->generateCertificateCode();
        $certificate = new Certificate($user['id'], $course->getId(), $code);
        
        $pdfPath = $this->createCertificatePDF($user, $course, $code);
        $certificate->setPdfPath($pdfPath);
        
        $certificate = $this->certificateRepository->create($certificate);
        
        $this->mailService->sendCertificateEmail(
            $user['email'],
            $user['name'],
            $course->getTitle(),
            $code,
            $pdfPath
        );
        
        return $certificate;
    }
    
    /**
     * Generar código único de certificado
     */
    private function generateCertificateCode()
    {
        return 'FARM-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
    }
    
    /**
     * Crear PDF del certificado (simple HTML a PDF)
     */
    private function createCertificatePDF($user, $course, $code)
    {
        $uploadsDir = __DIR__ . '/../../../uploads/certificates';
        
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }
        
        $filename = 'certificado_' . $user['id'] . '_' . $course->getId() . '_' . time() . '.html';
        $filepath = $uploadsDir . '/' . $filename;
        
        $html = $this->getCertificateHTML($user['name'], $course->getTitle(), $code, date('d/m/Y'));
        
        file_put_contents($filepath, $html);
        
        return url('uploads/certificates/' . $filename);
    }
    
    /**
     * Plantilla HTML del certificado
     */
    private function getCertificateHTML($userName, $courseName, $code, $date)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Certificado de Finalización</title>
            <style>
                body {
                    font-family: "Georgia", serif;
                    margin: 0;
                    padding: 40px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }
                .certificate {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    padding: 60px;
                    border-radius: 20px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    border: 10px solid #f0f0f0;
                    position: relative;
                }
                .certificate::before {
                    content: "";
                    position: absolute;
                    top: 20px;
                    left: 20px;
                    right: 20px;
                    bottom: 20px;
                    border: 2px solid #667eea;
                    border-radius: 10px;
                    pointer-events: none;
                }
                .header {
                    text-align: center;
                    margin-bottom: 40px;
                }
                .header h1 {
                    font-size: 48px;
                    color: #667eea;
                    margin: 0;
                    letter-spacing: 2px;
                }
                .header p {
                    font-size: 18px;
                    color: #666;
                    margin: 10px 0;
                }
                .content {
                    text-align: center;
                    margin: 40px 0;
                }
                .content p {
                    font-size: 20px;
                    color: #333;
                    line-height: 1.8;
                }
                .recipient {
                    font-size: 36px;
                    color: #764ba2;
                    font-weight: bold;
                    margin: 30px 0;
                    padding: 20px;
                    border-bottom: 3px solid #667eea;
                }
                .course-name {
                    font-size: 28px;
                    color: #667eea;
                    font-weight: bold;
                    margin: 30px 0;
                }
                .footer {
                    margin-top: 60px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .date, .code {
                    text-align: center;
                }
                .date strong, .code strong {
                    display: block;
                    color: #667eea;
                    font-size: 14px;
                    margin-bottom: 10px;
                }
                .code {
                    font-family: monospace;
                    font-size: 16px;
                    color: #333;
                }
                @media print {
                    body { background: white; padding: 0; }
                    .certificate { box-shadow: none; }
                }
            </style>
        </head>
        <body>
            <div class="certificate">
                <div class="header">
                    <h1>CONSTANCIA</h1>
                    <p>de Finalización de Curso</p>
                </div>
                
                <div class="content">
                    <p>Se otorga la presente constancia a:</p>
                    <div class="recipient">' . htmlspecialchars($userName) . '</div>
                    
                    <p>Por haber completado exitosamente el curso:</p>
                    <div class="course-name">' . htmlspecialchars($courseName) . '</div>
                    
                    <p>Cumpliendo satisfactoriamente con todos los requisitos establecidos.</p>
                </div>
                
                <div class="footer">
                    <div class="date">
                        <strong>FECHA DE EMISIÓN</strong>
                        <div>' . $date . '</div>
                    </div>
                    <div class="code">
                        <strong>CÓDIGO DE VERIFICACIÓN</strong>
                        <div>' . $code . '</div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';
    }
    
    /**
     * Obtener certificado por código
     */
    public function getCertificateByCode($code)
    {
        return $this->certificateRepository->findByCode($code);
    }
    
    /**
     * Obtener certificado por usuario y curso
     */
    public function getCertificateByUserAndCourse($user_id, $course_id)
    {
        return $this->certificateRepository->findByUserAndCourse($user_id, $course_id);
    }
}
