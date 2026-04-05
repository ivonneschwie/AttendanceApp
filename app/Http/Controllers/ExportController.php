<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    public function exportRoomAttendance($roomCode, $listId)
    {
        $attendanceList = $this->database->getReference('attendance/' . $roomCode . '/' . $listId)->getValue();
        $studentsData = $attendanceList['students'] ?? [];
        $listName = $attendanceList['name'] ?? 'attendance';

        $users = $this->database->getReference('users')->getValue() ?? [];
        
        $data = [];
        foreach ($studentsData as $studentUid => $details) {
            $user = $users[$studentUid] ?? null;
            $data[] = [
                'student_id' => $user['schoolId'] ?? $studentUid,
                'name' => $user ? ($user['firstName'] . ' ' . $user['lastName']) : 'N/A',
                'email' => $user['email'] ?? 'N/A',
                'time_in' => isset($details['time_in']) ? date('Y-m-d H:i:s', $details['time_in']) : 'N/A',
                'time_out' => isset($details['time_out']) && $details['time_out'] ? date('Y-m-d H:i:s', $details['time_out']) : 'N/A',
            ];
        }

        return $this->exportToExcel('Room_Attendance_' . $listName, ['Student ID', 'Name', 'Email', 'Time In', 'Time Out'], $data);
    }

    public function exportEventAttendance($eventId)
    {
        $event = $this->database->getReference('events/' . $eventId)->getValue();
        $studentsData = $this->database->getReference('event-attendance/' . $eventId . '/students')->getValue() ?? [];
        $eventName = $event['name'] ?? 'event';

        $users = $this->database->getReference('users')->getValue() ?? [];

        $data = [];
        foreach ($studentsData as $studentUid => $details) {
            $user = $users[$studentUid] ?? null;
            $data[] = [
                'student_id' => $user['schoolId'] ?? $studentUid,
                'name' => $user ? ($user['firstName'] . ' ' . $user['lastName']) : 'N/A',
                'email' => $user['email'] ?? 'N/A',
                'time_in' => isset($details['time_in']) ? date('Y-m-d H:i:s', $details['time_in']) : 'N/A',
                'time_out' => isset($details['time_out']) && $details['time_out'] ? date('Y-m-d H:i:s', $details['time_out']) : 'N/A',
            ];
        }

        return $this->exportToExcel('Event_Attendance_' . $eventName, ['Student ID', 'Name', 'Email', 'Time In', 'Time Out'], $data);
    }

    private function exportToExcel($fileName, $headers, $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($data, null, 'A2');

        // Auto-size columns
        foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
