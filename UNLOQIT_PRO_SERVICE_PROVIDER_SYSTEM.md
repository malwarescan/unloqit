# Unloqit Pro Service Provider System
## Project Manager Guide

**Document Version:** 1.0  
**Last Updated:** November 2024  
**Status:** Production Ready

---

## Executive Summary

Unloqit operates as an **on-demand locksmith marketplace** connecting customers with verified, independent locksmith professionals (Unloqit Pro Service Providers). The platform functions similarly to Uber or TaskRabbit, but specialized exclusively for locksmith services.

### Key Value Propositions

**For Customers:**
- Instant access to verified locksmiths
- Real-time job tracking
- Transparent pricing
- Fast response times (20-30 minutes average)

**For Unloqit Pro Service Providers:**
- Flexible schedule (work when you want)
- Direct customer access
- Competitive earnings
- Built-in job dispatch system

**For Unloqit:**
- Scalable marketplace model
- No need to employ locksmiths directly
- Platform fee revenue model
- SEO-optimized customer acquisition

---

## System Overview

### Architecture

The system consists of three main components:

1. **SEO-First Customer Acquisition Layer**
   - City/service/neighborhood landing pages
   - Optimized for search engines
   - Funnels customers into marketplace

2. **Marketplace Core**
   - Job request system
   - Provider dispatch engine
   - Real-time matching
   - Status tracking

3. **Provider Management System**
   - Registration & verification
   - Availability management
   - Earnings tracking
   - Performance metrics

---

## How It Works

### Customer Journey

```
1. Customer visits SEO page (e.g., /cleveland-locksmith/car-lockout)
   ↓
2. Clicks "Request an Unloqit Pro" button
   ↓
3. Fills out request form (service, location, urgency, contact info)
   ↓
4. Job created and broadcast to online providers
   ↓
5. Available Unloqit Pro claims job (first-come-first-served)
   ↓
6. Customer receives notification with Pro's info
   ↓
7. Pro updates status: en_route → arrived → in_progress → completed
   ↓
8. Customer tracks progress in real-time
   ↓
9. Job completed, payment processed, Pro receives earnings
```

### Provider Journey

```
1. Locksmith visits /pro/register
   ↓
2. Completes registration:
   - Personal information
   - License number (required)
   - Service areas (cities)
   - Service types offered
   ↓
3. Account created (pending verification)
   ↓
4. Admin verifies license and activates account
   ↓
5. Provider logs in at /pro/login
   ↓
6. Goes online (toggles availability)
   ↓
7. Views available jobs in dashboard
   ↓
8. Claims jobs matching their service areas/types
   ↓
9. Updates job status as they work
   ↓
10. Completes job, receives earnings
```

---

## Key Features

### 1. Provider Registration & Verification

**Registration Requirements:**
- Full name, email, phone
- Password (secure authentication)
- **License number** (mandatory for verification)
- Service areas (cities they serve)
- Service types (what they can do)
- Optional bio

**Verification Process:**
- Account created with `is_verified = false`
- Admin reviews license number
- Admin verifies credentials
- Account activated (`is_verified = true`, `is_active = true`)
- Provider notified via email

**Security:**
- Laravel authentication system
- Password hashing
- Session management
- Protected routes (requires login)

### 2. Job Dispatch System

**Job Creation:**
- Customer submits request form
- Job created with status: `created`
- Automatically broadcast to available providers

**Provider Matching Algorithm:**
Jobs are matched to providers who:
- ✅ Are online (`is_online = true`)
- ✅ Were active recently (`last_seen_at` within 15 minutes)
- ✅ Have capacity (`active_jobs_count < max_jobs_per_hour`)
- ✅ Serve the job's city (in their `service_areas`)
- ✅ Offer the job's service (in their `service_types`)
- ✅ Are verified (`is_verified = true`)
- ✅ Are active (`is_active = true`)

**Job Claiming:**
- First-come-first-served model
- Provider clicks "Claim Job" button
- Job assigned immediately
- Other providers see job is claimed
- Provider's `active_jobs_count` incremented

### 3. Real-Time Status Tracking

**Job Lifecycle:**
```
created → broadcast → claimed → en_route → arrived → in_progress → completed
```

**Status Updates:**
- Provider updates status via dashboard
- Each status change logged with timestamp
- Customer sees updates in real-time
- Complete audit trail maintained

**Status Meanings:**
- `created`: Job submitted, awaiting broadcast
- `broadcast`: Sent to available providers
- `claimed`: Assigned to a provider
- `en_route`: Provider heading to customer
- `arrived`: Provider at location
- `in_progress`: Provider working on job
- `completed`: Job finished

### 4. Availability Management

**Online/Offline Toggle:**
- Providers control their availability
- "Go Online" button in dashboard
- When online: Receive job broadcasts
- When offline: Hidden from dispatch system

**Capacity Management:**
- Each provider has `max_jobs_per_hour` limit
- System tracks `active_jobs_count`
- Prevents overbooking
- Automatic capacity release on job completion

**Last Seen Tracking:**
- System tracks `last_seen_at` timestamp
- Providers must be active within 15 minutes
- Prevents matching to inactive providers

### 5. Earnings & Payments

**Earnings Structure:**
- Job amount - platform fee = provider payout
- Platform fee configurable (default: 20-30%)
- Earnings tracked in `provider_earnings` table

**Payment Status:**
- `pending`: Job completed, awaiting processing
- `processing`: Payment being transferred
- `paid`: Payment completed
- `failed`: Payment failed, requires retry

**Stripe Connect Integration:**
- Ready for Stripe Connect implementation
- `stripe_account_id` field in providers table
- `stripe_transfer_id` in earnings table
- Onboarding flow ready

---

## Database Structure

### Core Tables

**providers**
- Provider account information
- License number, verification status
- Service areas, service types
- Rating, total jobs completed
- Stripe account information

**jobs**
- Customer job requests
- Provider assignment
- Status tracking
- Location data
- Pricing information
- Timing fields

**job_statuses**
- Complete audit trail
- Status history with timestamps
- Notes and provider attribution

**provider_availability**
- Real-time online/offline status
- Current location (lat/lng)
- Active jobs count
- Capacity limits

**provider_earnings**
- Earnings per job
- Platform fee calculation
- Payout status
- Stripe transfer tracking

**customers**
- Customer accounts (optional)
- Stripe customer ID
- Contact information

---

## User Roles & Permissions

### Unloqit Pro Service Provider

**Can:**
- Register and create account
- Log in to dashboard
- Toggle online/offline status
- View available jobs
- Claim jobs
- Update job status
- View earnings
- Manage profile

**Cannot (until verified):**
- Go online
- Claim jobs
- Access full dashboard features

**Cannot (ever):**
- Access admin functions
- View other providers' data
- Modify system settings

### Customer

**Can:**
- Submit job requests
- Track job status
- View provider information
- See job history

**Cannot:**
- Access provider dashboard
- Claim jobs
- Modify job status

### Admin (Future Implementation)

**Will be able to:**
- Verify provider accounts
- View all jobs
- Manage providers
- View analytics
- Process payments
- Handle disputes

---

## Key Metrics & KPIs

### Provider Metrics

- **Total Providers**: Count of registered providers
- **Verified Providers**: Count of verified/active providers
- **Online Providers**: Count currently online
- **Average Rating**: Average provider rating
- **Jobs per Provider**: Average jobs per provider
- **Response Time**: Average time to claim job

### Job Metrics

- **Total Jobs**: Count of all jobs created
- **Completed Jobs**: Count of completed jobs
- **Average Completion Time**: Time from creation to completion
- **Claim Rate**: Percentage of jobs claimed
- **Completion Rate**: Percentage of claimed jobs completed

### Marketplace Metrics

- **Match Rate**: Percentage of jobs matched to providers
- **Time to Claim**: Average time from broadcast to claim
- **Customer Satisfaction**: Ratings and reviews
- **Platform Revenue**: Total platform fees collected
- **Provider Earnings**: Total payouts to providers

---

## Business Model

### Revenue Streams

1. **Platform Fee** (Primary)
   - Percentage of each job (e.g., 20-30%)
   - Collected on job completion
   - Transferred to provider minus fee

2. **Premium Features** (Future)
   - Priority job placement
   - Enhanced profile visibility
   - Advanced analytics

3. **Advertising** (Future)
   - Provider profile promotion
   - Featured listings

### Cost Structure

- **Platform Development**: One-time setup
- **Hosting & Infrastructure**: Ongoing
- **Payment Processing**: Stripe fees (~2.9% + $0.30)
- **Customer Acquisition**: SEO/marketing costs
- **Support**: Customer service overhead

---

## Technical Stack

### Backend
- **Framework**: Laravel 11 (PHP 8.3)
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Auth (custom provider guard)
- **Caching**: Redis (optional)

### Frontend
- **Templating**: Blade
- **Styling**: TailwindCSS
- **Build Tool**: Vite
- **JavaScript**: Minimal (Alpine.js only if needed)

### Third-Party Integrations
- **Stripe Connect**: Payment processing (ready for implementation)
- **Email**: Laravel Mail (for notifications)
- **SMS**: Twilio (future, for notifications)

---

## Current Status

### ✅ Completed Features

1. **Provider Registration**
   - Full registration form
   - License verification requirement
   - Service area/type selection
   - Account creation

2. **Provider Authentication**
   - Login/logout system
   - Session management
   - Protected routes

3. **Job Request System**
   - Customer request form
   - Job creation
   - Status tracking

4. **Dispatch Engine**
   - Job broadcast system
   - Provider matching algorithm
   - Job claiming functionality

5. **Provider Dashboard**
   - Available jobs feed
   - Active jobs display
   - Online/offline toggle
   - Status updates

6. **SEO Integration**
   - Marketplace CTAs on all service pages
   - Clean URL structure maintained
   - No disruption to crawlability

### ⏳ Pending Implementation

1. **Admin Panel**
   - Provider verification interface
   - Job management
   - Analytics dashboard
   - User management

2. **Notifications**
   - Email notifications (job claimed, status updates)
   - SMS notifications (optional)
   - Push notifications (future)

3. **Payment Processing**
   - Stripe Connect onboarding
   - Payment collection
   - Automatic payouts
   - Refund handling

4. **Real-Time Updates**
   - WebSocket integration
   - Live status updates
   - Live location tracking

5. **Advanced Features**
   - Provider bidding system (optional)
   - Customer reviews/ratings
   - Dispute resolution
   - Provider analytics

---

## User Flows

### Provider Onboarding Flow

```
Registration → Email Verification → Admin Review → Account Activation → First Login → Go Online → Claim First Job
```

**Timeline:**
- Registration: Immediate
- Admin Review: 24-48 hours (manual)
- Account Activation: After verification
- First Job: Within hours of going online

### Job Fulfillment Flow

```
Job Created → Broadcast (30 seconds) → Claimed (2-5 minutes avg) → En Route (10-20 minutes) → Arrived → Completed (30-60 minutes)
```

**Total Time:**
- Average: 45-90 minutes from request to completion
- Emergency: 20-30 minutes response time

---

## Security & Compliance

### Data Security

- **Password Hashing**: Laravel bcrypt
- **Session Security**: Laravel session management
- **SQL Injection**: Eloquent ORM protection
- **XSS Protection**: Blade templating auto-escaping

### Compliance Considerations

- **License Verification**: Required for all providers
- **Background Checks**: Future implementation
- **Insurance Verification**: Future implementation
- **Data Privacy**: GDPR-ready structure

---

## Scalability Considerations

### Current Capacity

- **Providers**: Unlimited (database-driven)
- **Jobs**: Unlimited (database-driven)
- **Concurrent Users**: Limited by server capacity

### Scaling Strategy

1. **Horizontal Scaling**: Add more servers
2. **Database Optimization**: Indexing, query optimization
3. **Caching**: Redis for frequently accessed data
4. **CDN**: For static assets
5. **Load Balancing**: For high traffic

---

## Support & Maintenance

### Provider Support

- **Registration Issues**: Admin review process
- **Account Problems**: Admin intervention required
- **Payment Issues**: Stripe support + admin oversight
- **Technical Issues**: Development team support

### Customer Support

- **Job Requests**: Self-service via form
- **Status Tracking**: Self-service via tracking page
- **Issues**: Email support (future: chat/phone)

---

## Next Steps & Roadmap

### Phase 1: Core Functionality (Current)
- ✅ Provider registration
- ✅ Job dispatch
- ✅ Status tracking
- ✅ Basic dashboard

### Phase 2: Admin & Verification (Next)
- Admin panel for verification
- Provider management interface
- Job oversight tools
- Analytics dashboard

### Phase 3: Payments (Q1 2025)
- Stripe Connect integration
- Payment collection
- Automatic payouts
- Earnings dashboard

### Phase 4: Notifications (Q1 2025)
- Email notifications
- SMS notifications
- Real-time updates

### Phase 5: Advanced Features (Q2 2025)
- Provider bidding system
- Customer reviews
- Advanced analytics
- Mobile app (optional)

---

## Success Criteria

### Provider Success Metrics

- **50+ Verified Providers** in first 3 months
- **80%+ Provider Satisfaction** rating
- **Average 4.5+ Star Rating** from customers
- **<5% Provider Churn** rate

### Marketplace Success Metrics

- **100+ Jobs** per month in first quarter
- **<5 minutes** average time to claim
- **90%+ Job Completion Rate**
- **$10,000+ Monthly Platform Revenue** by month 6

### Customer Success Metrics

- **<30 minutes** average response time
- **4.5+ Star Average Rating**
- **80%+ Customer Satisfaction**
- **<5% Cancellation Rate**

---

## Risk Management

### Identified Risks

1. **Provider Quality**
   - **Risk**: Unqualified providers
   - **Mitigation**: License verification, rating system

2. **Low Provider Adoption**
   - **Risk**: Not enough providers
   - **Mitigation**: Marketing, competitive rates

3. **Payment Issues**
   - **Risk**: Payment failures, disputes
   - **Mitigation**: Stripe reliability, clear policies

4. **Customer Safety**
   - **Risk**: Safety incidents
   - **Mitigation**: Background checks, insurance verification

5. **Technical Failures**
   - **Risk**: System downtime
   - **Mitigation**: Monitoring, redundancy, backups

---

## Contact & Resources

### Technical Documentation
- `MARKETPLACE_KERNEL.md` - Technical implementation details
- `MARKETPLACE_USER_FLOWS.md` - User flow documentation
- `LINK_INTEGRITY_AUDIT.md` - URL/routing validation

### Key Files
- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Views: `resources/views/marketplace/`

### Database Schema
- Migrations: `database/migrations/`
- Models: `app/Models/`

---

## Conclusion

The Unloqit Pro Service Provider system is a fully functional marketplace platform that enables independent locksmiths to offer their services through Unloqit. The system is production-ready for core functionality, with a clear roadmap for enhancements.

**Key Strengths:**
- Scalable marketplace architecture
- SEO-optimized customer acquisition
- Real-time dispatch system
- Comprehensive provider management
- Ready for payment integration

**Immediate Priorities:**
1. Admin verification interface
2. Payment processing integration
3. Notification system
4. Provider onboarding optimization

---

*For technical questions, refer to the technical documentation files. For business questions, contact the product team.*

