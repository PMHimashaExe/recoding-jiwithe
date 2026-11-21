<div align="center">

# 🎓 UniReplay
### *Learn. Revisit. Replay.*

**The Next-Generation Centralized University Lecture Archive & AI Study Engine**

[![Laravel 13](https://img.shields.io/badge/Laravel-13.43.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.5](https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![MySQL 8](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tests Passing](https://img.shields.io/badge/Tests-100%25_Passing_(48_Assertions)-27B58A?style=for-the-badge&logo=checkmarx&logoColor=white)](#-automated-testing--code-quality)
[![License MIT](https://img.shields.io/badge/License-MIT-F59E0B?style=for-the-badge)](LICENSE)

<br/>

<p align="center">
  <img src="public/images/hero.jpg" alt="UniReplay Hero" width="850" style="border-radius: 18px; border: 1px solid #1E283D; box-shadow: 0 20px 50px rgba(0,0,0,0.8);" />
</p>

</div>

---

## 📌 Overview

**UniReplay** is an editorial, modern EdTech web platform engineered for universities. It replaces messy, expired Google Drive links, lost Zoom recordings, and noisy WhatsApp chats with a **single, centralized, faculty-moderated lecture streaming platform**.

UniReplay ensures **academic equity**: whether an undergraduate missed an 8 AM lecture due to transit disruptions, illness, or clashing timetables, every lecture is preserved, vetted, organized by part, and instantly synthesized into exam revision notes.

---

## ✨ Key Features & Innovations

### 1. 🛡️ Two-Tier Verification & Role-Based Access Control
* **Student Verification Pipeline:** Newly registered undergraduates enter a `pending` status. They cannot stream protected lecture videos until approved by faculty.
* **Batch-Scoped Administration:** Faculty Administrators are assigned to specific graduating batches (e.g., *Batch 2026 - Data & Computing*). Admins review student verification requests and student lecture uploads with structured rejection notices.
* **Universal Cross-Batch Archive:** While moderation is batch-assigned, **access is universal**. Verified students can freely explore and stream lectures from *any* batch or year across the campus.

### 2. 🧩 Multipart Session Architecture (Zero Duplicate Ingestion)
* Subjects contain **Lecture Sessions** (e.g. *Lecture #4 - Raft Consensus*).
* A single session can contain multiple sequenced **Recording Parts** (*Part 1*, *Part 2*).
* Enforces a composite relational database constraint: `UNIQUE [session_id + part_number]`. Duplicate part numbers are mathematically blocked at the database layer.

### 3. 🎬 Cinematic Video Theater & Protected Seeking
* Built with a custom **HTTP 206 Partial-Content Byte-Range Streaming Driver**.
* Students can seek forward and backward across 1080p recordings smoothly without downloading the entire 500MB+ file.
* Video files are protected in private storage — raw file paths are never exposed to clients.
* Seamless switching between Part 1, Part 2, and companion study slides.

### 4. 🧠 Flagship Innovation: Autopilot AI Study Notes & Exam Prep Engine
Watching a 2-hour video immediately before an examination is impractical. UniReplay features an integrated, context-aware **AI Study Engine** (`App\Services\LectureNoteGeneratorService`) that synthesizes video sessions into 3 distinct formats with zero external API delay:
* **📚 Comprehensive Academic Study Guide:** Executive synopsis, 5 high-yield key takeaways, deep conceptual breakdowns with architectural diagrams, formal definitions, and probable 10-mark examination questions with model answers.
* **⚡ Rapid Exam Cram Sheet:** 5-minute pre-exam revision sheet with critical formulas (e.g., Amdahl's Law, Big-O Complexity), high-frequency examination traps, and a Do's & Don'ts matrix.
* **❓ Active Recall & Flashcards:** Cognitive spaced repetition Q&A flashcard sets, True/False concept verification with detailed explanations, and scenario viva challenges.
* **✍️ Personal Reflections Drawer:** Students can append personal handwritten remarks (e.g., *"Lecturer emphasized Slide 18 for Mid-term"*).
* **📥 One-Click Export:** Copy formatted markdown to clipboard or download `.md` files directly for **Notion** or **Obsidian**.
* **🗂️ "My AI Notes" Library:** Dedicated dashboard section aggregating all synthesized notes across modules.

### 5. 🎨 Bespoke Editorial Cyber-Slate Theme
* Inspired by modern developer platforms like Linear and Raycast.
* **Color System:**
  * Background: Deep Cosmic Slate (`#070913`)
  * Elevated Surfaces: Cyber Slate (`#0D1322` / `#0D1426`)
  * Primary Accent: Electric Cyan (`#38BDF8`)
  * Highlight Accent: Neon Amber (`#F59E0B`)
  * Dividers & Borders: Dark Steel (`#1E283D`)
  * Verified Badge: Mint Emerald (`#27B58A`)
* **Typography:** `Sora` for display headings and numbers; `Inter` for interface elements and body text.

### 6. ⚡ 1-Click Fast Switcher for Hackathon Judges
* A dedicated demo strip sits at the top of the interface allowing evaluators to switch roles instantly (`Admin Batch 2026`, `Admin Batch 2025`, `Student 1 (Verified)`, `Student 2 (Verified)`, `Student 3 (Pending)`) without filling login forms.

---

## 🛠️ Full-Stack Technology Stack

| Layer | Technology | Engineering Reason |
| :--- | :--- | :--- |
| **Backend** | **Laravel 13 (PHP 8.5)** | Clean MVC architecture, robust authentication, middleware, Eloquent ORM, and schema migrations. |
| **Database** | **MySQL 8.0+** | 3NF normalized schema, composite unique indices, and foreign key cascading. |
| **Frontend** | **Laravel Blade** | Fast Server-Side Rendering (SSR) without SPA bundle bloat. |
| **Styling** | **Tailwind CSS v4** | Modern utility-first CSS delivering a responsive, dark cyber-slate design system. |
| **Interactivity**| **Alpine.js (~15KB)** | Lightweight client reactivity for part switching, note generation status, and modals. |
| **Icons** | **Lucide Icons** | Clean, scalable modern vector SVG iconography. |
| **Streaming** | **HTTP 206 Partial Content** | Fast byte-range seeking for large lecture videos with private file shielding. |
| **AI Engine** | **Custom Service Layer** | Deterministic curriculum synthesis engine; 100% offline, zero API latency, zero costs. |
| **Testing** | **PHPUnit** | 9 comprehensive feature tests with 48 passing assertions. |
| **Code Style** | **Laravel Pint** | Automated PSR-12 code formatting. |

---

## 🗄️ Database Architecture

The database contains 10 normalized relational tables:

```
[batches] ──< [users] ──< [recordings]
    │            │             │
    ▼            ▼             ▼
[lecture_sessions] ───────< [lecture_notes]
    │            │
    ├──< [resources]
    ├──< [bookmarks]
    └──< [watch_histories]
```

* **`users`**: Academic identity, role (`student`, `admin`), verification state (`pending`, `verified`, `rejected`), assigned batch, student ID number.
* **`batches`**: Academic graduating classes (e.g., *Batch 2026 - Data & Computing*).
* **`subjects`**: Course modules with codes and descriptions (e.g., *CS301 - Distributed Systems*).
* **`lecture_sessions`**: Sessions delivered on specific dates with lecture numbers.
* **`recordings`**: Sequenced video parts. **Unique constraint: `[session_id + part_number]`**.
* **`resources`**: Companion files (Lecture presentation slides, reference PDFs, ZIPs).
* **`lecture_notes`**: Synthesized AI study guides, flashcards, cram sheets, and personal student annotations.
* **`bookmarks`**: Saved lecture bookmarks.
* **`watch_histories`**: Playback progress tracking powering the "Continue Watching" carousel.
* **`app_notifications`**: In-app alerts for student verification and recording review decisions.

---

## 🚀 Quick Start & Installation

### Prerequisites
* PHP >= 8.4 (PHP 8.5 recommended)
* Composer
* Node.js & npm
* MySQL Database

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/PMHimashaExe/recoding-jiwithe.git
cd recoding-jiwithe

# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your MySQL connection in `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unireplay
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Run Migrations & Seed Demo Data
```bash
php artisan migrate:fresh --seed
```
*This creates the entire schema, realistic batches, subjects, verified users, sessions, sample recordings, companion slides, and pre-synthesized AI study notes.*

### 4. Build Frontend Assets
```bash
npm run build
```

### 5. Launch Local Development Server
```bash
php artisan serve
```
Open **`http://127.0.0.1:8000`** in your browser.

---

## 👥 Demo Accounts (Evaluator Quick Access)

Use the 1-Click Fast Switcher banner at the top of the page, or login manually:

| Role | Email | Password | Access Scope |
| :--- | :--- | :--- | :--- |
| **Faculty Admin (Batch 2026)** | `admin@unireplay.edu` | `password` | Batch 2026 Moderation + Full University Archive |
| **Faculty Admin (Batch 2025)** | `admin2@unireplay.edu` | `password` | Batch 2025 Moderation + Full University Archive |
| **Verified Student 1** | `student1@unireplay.edu` | `password` | Full Archive, AI Notes, Uploads, Bookmarks |
| **Verified Student 2** | `student2@unireplay.edu` | `password` | Full Archive, AI Notes, Uploads, Bookmarks |
| **Pending Student** | `student3@unireplay.edu` | `password` | Verification Holding Screen (Gated Access) |

---

## 🧪 Automated Testing & Code Quality

UniReplay maintains strict test-driven reliability. Run the test suite:

```bash
php artisan test --compact
```

### Test Coverage Results:
```
✓ test_complete_hackathon_workflow
✓ test_verified_student_can_generate_ai_lecture_note
✓ test_student_can_generate_different_note_formats
✓ test_student_can_save_personal_annotations
✓ test_student_can_download_note_as_markdown
✓ test_pending_student_cannot_generate_lecture_notes

Tests:    9 passed (48 assertions)
Duration: 0.51s
```

Run Laravel Pint code style verification:
```bash
vendor/bin/pint --format agent
```

---

## 🔮 Production Roadmap

1. **☁️ Scalable Object Storage Driver:** Transitioning local storage to Cloudflare R2 / AWS S3 with presigned chunked 5GB resumable uploads.
2. **🏛️ Multi-Campus Federation:** Partitioning the database across national state and private universities with unified Single-Sign-On (SSO).
3. **📱 Progressive Web App (PWA):** Encrypted local video caching for rural undergraduates with intermittent network bandwidth.
4. **🎙️ Speech-to-Text Transcription:** Automated Whisper speech-to-text integration for real-time video subtitles and searchable audio transcripts.

---

## 📄 Presentation & Documentation Artifacts

* 📊 **PowerPoint Presentation:** [`UniReplay_5Min_Pitch.pptx`](UniReplay_5Min_Pitch.pptx) *(Ready for Microsoft PowerPoint & Google Slides)*
* 🌐 **Interactive Web Slides:** [`presentation.html`](presentation.html) *(Built-in 5-minute stopwatch & Spoken Sinhala speaker notes)*
* 📄 **Complete Technical Document:** [`UniReplay_Project_Documentation.docx`](UniReplay_Project_Documentation.docx) *(Full architectural specification)*
* 📄 **Sinhala & English Guide:** [`UniReplay_Sinhala_English_Doc.docx`](UniReplay_Sinhala_English_Doc.docx) *(Bilingual reference manual)*

---

## 📜 License

This project was built for the university hackathon and is open-sourced under the [MIT License](LICENSE).

<div align="center">
  <sub>Built with ❤️ by the UniReplay Engineering Team</sub>
</div>
