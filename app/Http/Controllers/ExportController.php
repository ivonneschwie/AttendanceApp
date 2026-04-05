<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use DateTime;
use DateTimeZone;
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
                'year_level' => $user['yearLevel'] ?? 'N/A',
                'block' => $user['block'] ?? 'N/A',
                'time_in' => isset($details['time_in']) ? $this->convertToGmtPlus8($details['time_in']) : 'N/A',
                'time_out' => isset($details['time_out']) && $details['time_out'] ? $this->convertToGmtPlus8($details['time_out']) : 'N/A',
            ];
        }

        return $this->exportToExcel('Room_Attendance_' . $listName, ['Student ID', 'Name', 'Year Level', 'Block', 'Time In', 'Time Out'], $data);
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
                'year_level' => $user['yearLevel'] ?? 'N/A',
                'block' => $user['block'] ?? 'N/A',
                'time_in' => isset($details['time_in']) ? $this->convertToGmtPlus8($details['time_in']) : 'N/A',
                'time_out' => isset($details['time_out']) && $details['time_out'] ? $this->convertToGmtPlus8($details['time_out']) : 'N/A',
            ];
        }

        return $this->exportToExcel('Event_Attendance_' . $eventName, ['Student ID', 'Name', 'Year Level', 'Block', 'Time In', 'Time Out'], $data);
    }

    private function convertToGmtPlus8($timestamp)
    {
        $datetime = new DateTime('@' . $timestamp);
        $datetime->setTimezone(new DateTimeZone('Asia/Manila')); // GMT+8
        return $datetime->format('Y-m-d H:i:s');
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
