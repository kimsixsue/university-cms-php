# ERD 초안

## 1. 문서 목적

본 문서는 대학 부속홈페이지 통합관리 CMS의 1차 DB 설계를 정리하기 위한 ERD 초안입니다.

요구사항정의서에서 정리한 관리자 인증, 권한관리, 사이트 관리, 메뉴 관리, 페이지 관리, 게시판 관리, 첨부파일 관리, 배너/팝업 관리, 관리자 작업 로그 기능을 기준으로 필요한 테이블과 관계를 정의합니다.

## 2. 주요 테이블 목록

| 테이블명           | 설명          |
| -------------- | ----------- |
| users          | 관리자 계정      |
| roles          | 권한          |
| user_roles     | 관리자와 권한 연결  |
| sites          | 부속 홈페이지     |
| site_users     | 사이트와 관리자 연결 |
| menus          | 사이트별 메뉴     |
| pages          | 일반 페이지      |
| page_histories | 페이지 수정 이력   |
| boards         | 게시판         |
| posts          | 게시글         |
| files          | 첨부파일        |
| banners        | 배너          |
| popups         | 팝업          |
| admin_logs     | 관리자 작업 로그   |

## 3. 테이블 상세

### 3.1 users

관리자 계정 정보를 저장합니다.

| 컬럼명        | 설명          |
| ---------- | ----------- |
| id         | 관리자 ID      |
| username   | 로그인 아이디     |
| password   | 해시 처리된 비밀번호 |
| name       | 관리자 이름      |
| email      | 이메일         |
| status     | 계정 상태       |
| created_at | 생성일시        |
| updated_at | 수정일시        |

### 3.2 roles

관리자 권한 정보를 저장합니다.

| 컬럼명         | 설명    |
| ----------- | ----- |
| id          | 권한 ID |
| name        | 권한명   |
| description | 권한 설명 |

예시 권한:

| 권한명           | 설명     |
| ------------- | ------ |
| super_admin   | 최고관리자  |
| site_admin    | 사이트관리자 |
| content_admin | 콘텐츠관리자 |

### 3.3 user_roles

관리자와 권한의 연결 정보를 저장합니다.

| 컬럼명     | 설명     |
| ------- | ------ |
| user_id | 관리자 ID |
| role_id | 권한 ID  |

### 3.4 sites

부속 홈페이지 정보를 저장합니다.

| 컬럼명         | 설명     |
| ----------- | ------ |
| id          | 사이트 ID |
| site_code   | 사이트 코드 |
| name        | 사이트명   |
| description | 사이트 설명 |
| status      | 사용 상태  |
| created_at  | 생성일시   |
| updated_at  | 수정일시   |

예시 사이트:

| 사이트명    | 설명        |
| ------- | --------- |
| 문헌정보학과  | 학과 홈페이지   |
| 미래융합연구소 | 연구소 홈페이지  |
| 산학협력단   | 부속기관 홈페이지 |

### 3.5 site_users

특정 관리자가 어떤 사이트를 담당하는지 저장합니다.

| 컬럼명     | 설명     |
| ------- | ------ |
| site_id | 사이트 ID |
| user_id | 관리자 ID |

### 3.6 menus

사이트별 메뉴 정보를 저장합니다.

| 컬럼명        | 설명       |
| ---------- | -------- |
| id         | 메뉴 ID    |
| site_id    | 사이트 ID   |
| parent_id  | 상위 메뉴 ID |
| name       | 메뉴명      |
| type       | 연결 유형    |
| target_id  | 연결 대상 ID |
| url        | 외부 링크    |
| sort_order | 정렬 순서    |
| is_visible | 노출 여부    |
| created_at | 생성일시     |
| updated_at | 수정일시     |

메뉴 연결 유형 예시:

| type  | 의미        |
| ----- | --------- |
| page  | 일반 페이지 연결 |
| board | 게시판 연결    |
| link  | 외부 링크 연결  |

### 3.7 pages

일반 페이지 정보를 저장합니다.

| 컬럼명        | 설명     |
| ---------- | ------ |
| id         | 페이지 ID |
| site_id    | 사이트 ID |
| title      | 페이지 제목 |
| content    | 페이지 내용 |
| created_by | 작성자    |
| updated_by | 수정자    |
| created_at | 생성일시   |
| updated_at | 수정일시   |

### 3.8 page_histories

페이지 수정 이력을 저장합니다.

| 컬럼명        | 설명      |
| ---------- | ------- |
| id         | 이력 ID   |
| page_id    | 페이지 ID  |
| title      | 수정 전 제목 |
| content    | 수정 전 내용 |
| changed_by | 수정자     |
| changed_at | 수정일시    |

### 3.9 boards

게시판 정보를 저장합니다.

| 컬럼명        | 설명     |
| ---------- | ------ |
| id         | 게시판 ID |
| site_id    | 사이트 ID |
| name       | 게시판명   |
| board_type | 게시판 유형 |
| status     | 사용 상태  |
| created_at | 생성일시   |
| updated_at | 수정일시   |

게시판 유형 예시:

| board_type | 의미   |
| ---------- | ---- |
| notice     | 공지사항 |
| file       | 자료실  |
| faq        | FAQ  |

### 3.10 posts

게시글 정보를 저장합니다.

| 컬럼명        | 설명     |
| ---------- | ------ |
| id         | 게시글 ID |
| board_id   | 게시판 ID |
| title      | 제목     |
| content    | 내용     |
| is_notice  | 공지글 여부 |
| view_count | 조회수    |
| created_by | 작성자    |
| updated_by | 수정자    |
| created_at | 생성일시   |
| updated_at | 수정일시   |

### 3.11 files

첨부파일 정보를 저장합니다.

| 컬럼명           | 설명       |
| ------------- | -------- |
| id            | 파일 ID    |
| post_id       | 게시글 ID   |
| original_name | 원본 파일명   |
| stored_name   | 저장 파일명   |
| file_path     | 파일 경로    |
| file_size     | 파일 크기    |
| file_ext      | 파일 확장자   |
| uploaded_by   | 업로드한 관리자 |
| created_at    | 생성일시     |

### 3.12 banners

사이트별 배너 정보를 저장합니다.

| 컬럼명        | 설명     |
| ---------- | ------ |
| id         | 배너 ID  |
| site_id    | 사이트 ID |
| title      | 배너 제목  |
| image_path | 이미지 경로 |
| link_url   | 연결 URL |
| start_date | 노출 시작일 |
| end_date   | 노출 종료일 |
| is_active  | 사용 여부  |
| created_at | 생성일시   |

### 3.13 popups

사이트별 팝업 정보를 저장합니다.

| 컬럼명        | 설명     |
| ---------- | ------ |
| id         | 팝업 ID  |
| site_id    | 사이트 ID |
| title      | 팝업 제목  |
| content    | 팝업 내용  |
| start_date | 노출 시작일 |
| end_date   | 노출 종료일 |
| is_active  | 사용 여부  |
| created_at | 생성일시   |

### 3.14 admin_logs

관리자 작업 로그를 저장합니다.

| 컬럼명         | 설명       |
| ----------- | -------- |
| id          | 로그 ID    |
| user_id     | 관리자 ID   |
| action      | 작업 유형    |
| target_type | 작업 대상 유형 |
| target_id   | 작업 대상 ID |
| ip_address  | IP 주소    |
| created_at  | 작업 일시    |

## 4. 주요 관계

* 하나의 사이트는 여러 개의 메뉴를 가질 수 있다.
* 하나의 사이트는 여러 개의 페이지를 가질 수 있다.
* 하나의 사이트는 여러 개의 게시판을 가질 수 있다.
* 하나의 게시판은 여러 개의 게시글을 가질 수 있다.
* 하나의 게시글은 여러 개의 첨부파일을 가질 수 있다.
* 하나의 페이지는 여러 개의 수정 이력을 가질 수 있다.
* 하나의 관리자는 여러 개의 권한을 가질 수 있다.
* 하나의 관리자는 여러 사이트를 담당할 수 있다.
* 관리자 작업은 admin_logs에 기록된다.

## 5. 향후 검토 사항

* 콘텐츠관리자의 권한 범위를 더 세분화할지 검토한다.
* 게시글 삭제를 실제 삭제로 처리할지, 상태값 변경으로 처리할지 검토한다.
* 첨부파일을 게시글 외에 페이지, 배너, 팝업에서도 공통으로 사용할지 검토한다.
* 페이지 수정 이력에서 롤백 기능까지 구현할지 검토한다.
* 관리자 작업 로그에 변경 전/후 데이터를 저장할지 검토한다.
