<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\Batch;
use App\Models\Bookmark;
use App\Models\LectureSession;
use App\Models\Recording;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\User;
use App\Models\WatchHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Batches
        $batch2024 = Batch::create([
            'batch_name' => 'Batch 2024 - Computer Science',
            'graduation_date' => '2024-12-20',
        ]);

        $batch2025 = Batch::create([
            'batch_name' => 'Batch 2025 - Software Engineering',
            'graduation_date' => '2025-11-30',
        ]);

        $batch2026 = Batch::create([
            'batch_name' => 'Batch 2026 - Data & Computing',
            'graduation_date' => '2026-12-15',
        ]);

        // 2. Create Subjects
        $se = Subject::create([
            'subject_name' => 'Software Engineering & Architecture',
            'subject_code' => 'SE301',
            'description' => 'Architectural patterns, domain-driven design, microservices, and continuous deployment methodologies.',
            'icon' => 'code-2',
        ]);

        $db = Subject::create([
            'subject_name' => 'Database Systems & Normalization',
            'subject_code' => 'CS204',
            'description' => 'Relational database theory, normalization from 1NF to 5NF, query optimization, and transaction ACID properties.',
            'icon' => 'database',
        ]);

        $cloud = Subject::create([
            'subject_name' => 'Cloud Computing & Infrastructure',
            'subject_code' => 'CS310',
            'description' => 'Container orchestration with Kubernetes, serverless paradigms, and distributed systems resilience.',
            'icon' => 'cloud',
        ]);

        $networks = Subject::create([
            'subject_name' => 'Computer Networks & Security',
            'subject_code' => 'CS208',
            'description' => 'Layered protocols, TCP congestion mechanics, packet routing, and TLS cryptographic handshakes.',
            'icon' => 'network',
        ]);

        $dsa = Subject::create([
            'subject_name' => 'Data Structures & Algorithms',
            'subject_code' => 'CS102',
            'description' => 'Advanced algorithmic complexity, balanced binary search trees, graph theory, and dynamic programming.',
            'icon' => 'binary',
        ]);

        $ai = Subject::create([
            'subject_name' => 'Artificial Intelligence & Neural Nets',
            'subject_code' => 'CS401',
            'description' => 'Statistical learning foundations, gradient descent, transformers, and neural network representations.',
            'icon' => 'cpu',
        ]);

        // 3. Create Users
        // Admin for Batch 2026
        $admin = User::create([
            'name' => 'Dr. Sarah Chen',
            'email' => 'admin@unireplay.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'verified',
            'batch_id' => $batch2026->id,
            'student_id_number' => 'FAC-7701',
            'avatar_color' => '#7657F6',
        ]);

        // Admin for Batch 2025
        $admin2 = User::create([
            'name' => 'Prof. David Miller',
            'email' => 'admin2@unireplay.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'verified',
            'batch_id' => $batch2025->id,
            'student_id_number' => 'FAC-5420',
            'avatar_color' => '#17171D',
        ]);

        // Verified Student 1 (Batch 2026)
        $alex = User::create([
            'name' => 'Alex Rivera',
            'email' => 'alex@student.unireplay.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'verified',
            'batch_id' => $batch2026->id,
            'student_id_number' => 'ST-2026-0412',
            'avatar_color' => '#7657F6',
        ]);

        // Verified Student 2 (Batch 2025)
        $elena = User::create([
            'name' => 'Elena Rostova',
            'email' => 'elena@student.unireplay.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'verified',
            'batch_id' => $batch2025->id,
            'student_id_number' => 'ST-2025-0891',
            'avatar_color' => '#27B58A',
        ]);

        // Pending Student (Waiting for Admin approval)
        $marcus = User::create([
            'name' => 'Marcus Thorne',
            'email' => 'marcus@student.unireplay.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'pending',
            'batch_id' => $batch2026->id,
            'student_id_number' => 'ST-2026-1044',
            'avatar_color' => '#B7A7FF',
        ]);

        // 4. Create Lecture Sessions & Recordings

        // Session 1: Software Engineering (Batch 2026)
        $sess1 = LectureSession::create([
            'subject_id' => $se->id,
            'batch_id' => $batch2026->id,
            'lecture_date' => '2026-11-14',
            'lecture_number' => 4,
            'title' => 'Microservices Architecture & Event-Driven Message Brokers',
            'description' => 'Deep dive into bounded contexts, asynchronous communication with Apache Kafka, sagas for distributed transactions, and circuit breaker patterns.',
        ]);

        $rec1_1 = Recording::create([
            'session_id' => $sess1->id,
            'part_number' => 1,
            'video_path' => 'recordings/sample-demo.mp4',
            'original_filename' => 'SE301_Lec04_Part1_Microservices.mp4',
            'status' => 'approved',
            'uploaded_by' => $alex->id,
            'reviewed_by' => $admin->id,
            'duration' => '42:15',
            'thumbnail_path' => null,
        ]);

        $rec1_2 = Recording::create([
            'session_id' => $sess1->id,
            'part_number' => 2,
            'video_path' => 'recordings/sample-demo.mp4',
            'original_filename' => 'SE301_Lec04_Part2_KafkaSagas.mp4',
            'status' => 'approved',
            'uploaded_by' => $alex->id,
            'reviewed_by' => $admin->id,
            'duration' => '38:40',
            'thumbnail_path' => null,
        ]);

        Resource::create([
            'session_id' => $sess1->id,
            'title' => 'Lecture 04 Slide Deck - Distributed Patterns',
            'file_path' => 'resources/se301-lecture-04.pdf',
            'type' => 'pdf',
            'file_size' => '4.2 MB',
        ]);

        Resource::create([
            'session_id' => $sess1->id,
            'title' => 'Microservices Architecture Diagram & Docker Compose',
            'file_path' => 'resources/architecture-samples.zip',
            'type' => 'zip',
            'file_size' => '12.8 MB',
        ]);

        Resource::create([
            'session_id' => $sess1->id,
            'title' => 'Martin Fowler: Microservice Prerequisites Article',
            'type' => 'link',
            'external_url' => 'https://martinfowler.com/articles/microservices.html',
            'file_size' => null,
        ]);

        // Session 2: Database Systems (Batch 2026)
        $sess2 = LectureSession::create([
            'subject_id' => $db->id,
            'batch_id' => $batch2026->id,
            'lecture_date' => '2026-11-18',
            'lecture_number' => 7,
            'title' => 'Relational Normalization: From 1NF to BCNF with Real-World Case Studies',
            'description' => 'Comprehensive walkthrough of functional dependencies, transitive dependencies, candidate keys, and step-by-step decomposition to Boyce-Codd Normal Form.',
        ]);

        $rec2_1 = Recording::create([
            'session_id' => $sess2->id,
            'part_number' => 1,
            'video_path' => 'recordings/sample-demo.mp4',
            'drive_url' => 'https://drive.google.com/file/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OGvE2upms/view?usp=sharing',
            'drive_file_id' => '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OGvE2upms',
            'original_filename' => 'CS204_Lec07_Part1_1NF_to_3NF.mp4',
            'status' => 'approved',
            'uploaded_by' => $alex->id,
            'reviewed_by' => $admin->id,
            'duration' => '51:00',
            'thumbnail_path' => null,
        ]);

        $rec2_2 = Recording::create([
            'session_id' => $sess2->id,
            'part_number' => 2,
            'video_path' => 'recordings/sample-demo.mp4',
            'original_filename' => 'CS204_Lec07_Part2_BCNF_Decomposition.mp4',
            'status' => 'approved',
            'uploaded_by' => $alex->id,
            'reviewed_by' => $admin->id,
            'duration' => '45:30',
            'thumbnail_path' => null,
        ]);

        Resource::create([
            'session_id' => $sess2->id,
            'title' => 'Normalization Quick Reference Sheet (1NF-BCNF)',
            'file_path' => 'resources/normalization-cheatsheet.pdf',
            'type' => 'pdf',
            'file_size' => '1.8 MB',
        ]);

        // Session 3: Cloud Infrastructure (Batch 2025)
        $sess3 = LectureSession::create([
            'subject_id' => $cloud->id,
            'batch_id' => $batch2025->id,
            'lecture_date' => '2026-11-19',
            'lecture_number' => 5,
            'title' => 'Kubernetes Pod Networking, Services & Ingress Controllers',
            'description' => 'Container network interfaces (CNI), overlay networks, ClusterIP vs NodePort, and configuring Traefik and Nginx Ingress routes.',
        ]);

        $rec3_1 = Recording::create([
            'session_id' => $sess3->id,
            'part_number' => 1,
            'video_path' => 'recordings/sample-demo.mp4',
            'original_filename' => 'CS310_Lec05_Part1_K8sNetworking.mp4',
            'status' => 'approved',
            'uploaded_by' => $elena->id,
            'reviewed_by' => $admin2->id,
            'duration' => '54:12',
            'thumbnail_path' => null,
        ]);

        Resource::create([
            'session_id' => $sess3->id,
            'title' => 'Kubernetes Cluster Manifests & Ingress Configs',
            'file_path' => 'resources/k8s-manifests.zip',
            'type' => 'zip',
            'file_size' => '850 KB',
        ]);

        // Session 4: Computer Networks (Batch 2025)
        $sess4 = LectureSession::create([
            'subject_id' => $networks->id,
            'batch_id' => $batch2025->id,
            'lecture_date' => '2026-11-20',
            'lecture_number' => 9,
            'title' => 'Transport Layer: TCP Three-Way Handshake, Flow Control & TLS 1.3',
            'description' => 'Analysis of Wireshark packet captures, sequence and acknowledgment numbers, sliding window flow control, and zero-RTT session resumption.',
        ]);

        $rec4_1 = Recording::create([
            'session_id' => $sess4->id,
            'part_number' => 1,
            'video_path' => 'recordings/sample-demo.mp4',
            'original_filename' => 'CS208_Lec09_Part1_TCP_TLS.mp4',
            'status' => 'approved',
            'uploaded_by' => $elena->id,
            'reviewed_by' => $admin2->id,
            'duration' => '48:10',
            'thumbnail_path' => null,
        ]);

        // Session 5: Data Structures (Batch 2026) - PENDING APPROVAL (Perfect for Admin demo!)
        $sess5 = LectureSession::create([
            'subject_id' => $dsa->id,
            'batch_id' => $batch2026->id,
            'lecture_date' => '2026-11-21',
            'lecture_number' => 11,
            'title' => 'Self-Balancing Trees: AVL Rotations & Red-Black Invariants',
            'description' => 'Implementation of single and double rotations in AVL trees, color-flip properties, and height-invariant mathematical proofs.',
        ]);

        $rec5_1 = Recording::create([
            'session_id' => $sess5->id,
            'part_number' => 1,
            'video_path' => 'recordings/sample-demo.mp4',
            'original_filename' => 'CS102_Lec11_Part1_AVL_Rotations.mp4',
            'status' => 'pending',
            'uploaded_by' => $alex->id,
            'reviewed_by' => null,
            'duration' => '44:20',
            'thumbnail_path' => null,
        ]);

        Resource::create([
            'session_id' => $sess5->id,
            'title' => 'AVL Tree Implementation Source Code (C++ & Java)',
            'file_path' => 'resources/avl-tree-source.zip',
            'type' => 'zip',
            'file_size' => '320 KB',
        ]);

        // Bookmarks & History for Alex
        Bookmark::create([
            'user_id' => $alex->id,
            'session_id' => $sess1->id,
        ]);

        Bookmark::create([
            'user_id' => $alex->id,
            'session_id' => $sess2->id,
        ]);

        WatchHistory::create([
            'user_id' => $alex->id,
            'recording_id' => $rec1_1->id,
            'progress_seconds' => 1450,
            'completed' => false,
            'last_watched_at' => now()->subHours(3),
        ]);

        WatchHistory::create([
            'user_id' => $alex->id,
            'recording_id' => $rec2_1->id,
            'progress_seconds' => 3060,
            'completed' => true,
            'last_watched_at' => now()->subDay(),
        ]);

        // Notifications
        AppNotification::create([
            'user_id' => $alex->id,
            'title' => 'Account Verified',
            'message' => 'Welcome to UniReplay! Your student account has been approved by administrator Dr. Sarah Chen.',
            'type' => 'account_approved',
            'link' => '/dashboard',
            'is_read' => true,
        ]);

        AppNotification::create([
            'user_id' => $alex->id,
            'title' => 'Recording Approved',
            'message' => 'Your upload "Microservices Architecture (Part 1)" has been approved and published to the lecture library.',
            'type' => 'recording_approved',
            'link' => '/lectures/'.$sess1->id,
            'is_read' => false,
        ]);

        AppNotification::create([
            'user_id' => $alex->id,
            'title' => 'Upload Received & Queued',
            'message' => 'Your upload "Self-Balancing Trees (Part 1)" is pending review by Batch 2026 admin.',
            'type' => 'system',
            'link' => '/my-uploads',
            'is_read' => false,
        ]);
    }
}
