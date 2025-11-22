# Unloqit Global Kernel v1.0
## Master Operating System for the Marketplace Platform

**Version:** 1.0  
**Last Updated:** November 2024  
**Status:** Active

---

## Overview

This document defines the unified operating system for Unloqit—a specialized, vertical gig marketplace platform connecting customers with verified Unloqit Pro Service Providers. All system components must conform to this kernel.

---

## A. GLOBAL MASTER META-KERNEL

### Core Identity

**Unloqit is NOT a traditional locksmith company.**

**Unloqit IS a specialized, vertical gig marketplace platform** that connects:
- Customers who need locksmith services
- Verified Unloqit Pro Service Providers (independent contractors)
- Real-time job dispatch and matching system
- SEO-optimized customer acquisition shell

### Unified Kernel Fusion

This global kernel fuses:
1. **Marketplace Kernel** - Gig marketplace logic and flows
2. **Content Kernel** - Human-authored, original, direct voice
3. **Frontend Aesthetics** - Anti-slop design, intentional UI
4. **Link Integrity Kernel** - Valid routes, no fictional URLs
5. **Provider System Kernel** - Provider lifecycle and management
6. **Admin Kernel** - Verification and oversight
7. **Payment Kernel** - Stripe Connect and earnings
8. **Mobile PWA Kernel** - Mobile-first, real-time interactions

### Rules for ALL Output

1. **NEVER** treat Unloqit like a traditional locksmith company
2. **ALWAYS** treat it as a live gig marketplace with:
   - Real-time dispatch
   - Job claiming
   - Provider verification
   - Earnings tracking
   - Customer tracking
3. **ALL content** must follow realistic, human, original, direct voice rules
4. **ALL UI** must follow anti-slop aesthetic engine
5. **ALL links** must map to valid routes—no fictional URLs
6. **ALL backend logic** must follow documented job/provider lifecycle
7. **ALL features** must maintain SEO-first acquisition without breaking marketplace flow
8. **ALL future features** must conform to marketplace DNA: liquidity, matching, dispatch, tracking

**Outputs violating ANY kernel rule cannot be generated.**

---

## B. ADMIN PANEL KERNEL

### Purpose

The admin system is the **regulatory layer** for the gig marketplace—not a "staff user" system. It governs marketplace safety, quality, and liquidity.

### Admin Responsibilities

**Provider Management:**
- Verify provider licenses
- Activate/deactivate providers
- Review provider credentials
- Monitor provider performance
- Handle provider disputes

**Job Oversight:**
- View all jobs and statuses
- Intervene in stuck jobs
- Monitor job completion rates
- Track response times
- Handle customer disputes

**Financial Management:**
- View all financial logs
- Process payments
- Adjust platform fees
- Handle refunds
- Monitor earnings

**Analytics & Compliance:**
- Dashboard analytics
- Provider metrics
- Job metrics
- Revenue tracking
- Compliance monitoring

### Admin UI Principles

1. **Clean, audit-focused, high-signal**
2. **Authoritative, precise, compliance-oriented language**
3. **Isolated and protected routes**
4. **Cannot perform customer or provider actions**
5. **Decisions reflect marketplace safety, quality, liquidity**

### Admin Behaviors Must Maintain

- Provider authenticity
- Customer safety
- Job flow continuity
- Payment integrity

### Admin Routes (To Be Implemented)

```
/admin/login
/admin/dashboard
/admin/providers
/admin/providers/{id}/verify
/admin/providers/{id}/activate
/admin/providers/{id}/deactivate
/admin/jobs
/admin/jobs/{id}
/admin/jobs/{id}/intervene
/admin/disputes
/admin/payments
/admin/analytics
/admin/settings
```

---

## C. PAYMENT + STRIPE CONNECT KERNEL

### Payment Model

Unloqit uses a **gig marketplace payout model** powered by Stripe Connect.

### Payment Flow

```
Job Created → Payment Authorized → Platform Fee Removed → Provider Payout → Paid
```

### Provider Requirements

Before receiving earnings, providers must have:
- `stripe_account_id` (Stripe Connect account)
- Payout capability enabled
- Verified identity
- Completed Stripe onboarding

### Payment Calculation

For every job:
```
Job Amount - Platform Fee = Provider Earnings
```

**Example:**
- Job: $150
- Platform Fee (20%): $30
- Provider Earnings: $120

### Payment Statuses

1. **pending** - Job completed, awaiting processing
2. **processing** - Payment being transferred via Stripe
3. **paid** - Payment completed successfully
4. **failed** - Payment failed, requires retry

### Payment Lifecycle

```
created → authorized → platform_fee_removed → provider_payout → paid
```

### Database Fields

**providers:**
- `stripe_account_id` - Stripe Connect account ID
- `stripe_onboarding_complete` - Boolean flag
- `stripe_account_status` - active, restricted, etc.

**provider_earnings:**
- `amount` - Total job amount
- `platform_fee` - Platform commission
- `payout_amount` - Amount to provider
- `stripe_transfer_id` - Stripe transfer ID
- `status` - pending, processing, paid, failed

**jobs:**
- `quoted_price` - Initial quote
- `final_price` - Final amount charged
- `payment_status` - pending, processing, paid, failed

### Payment Pages Language

Must use marketplace tone:
- "Earnings" (not "Payments")
- "Payout history" (not "Payment history")
- "Pending balance" (not "Unpaid balance")
- "Platform fee" (not "Commission")

### Compliance Requirements

- Stripe Connect transfers only
- Stripe fees accounted for
- Platform commission tracked
- Payout delays handled
- Adjustments logged
- Disputes processed
- Admin financial visibility

### Payment Routes (To Be Implemented)

```
/pro/earnings - Provider earnings dashboard
/pro/stripe/connect - Stripe Connect onboarding
/pro/stripe/onboarding-complete - Callback handler
/admin/payments - Admin payment oversight
/admin/payments/{id}/process - Manual payment processing
```

---

## D. MOBILE + PWA / REAL-TIME KERNEL

### Mobile-First Principle

**Desktop behavior is secondary; mobile-first behavior is primary.**

### Mobile-First Layout Requirements

1. **Thumb Zones**
   - Primary actions in thumb-reach areas
   - Bottom navigation for key actions
   - Sticky action buttons

2. **One-Handed Controls**
   - Large tap targets (min 44x44px)
   - Minimal typing required
   - Quick actions accessible

3. **Sticky Action Buttons**
   - "Go Online" toggle always visible (providers)
   - "Request Pro" button sticky (customers)
   - "Claim Job" button prominent (providers)

4. **Large Tap Targets**
   - Buttons minimum 44x44px
   - Spacing between interactive elements
   - Clear visual hierarchy

### Real-Time Behavior (Mandatory)

1. **Live Job Tracking**
   - Real-time status updates
   - ETA visibility
   - Location updates (future)

2. **Status Updates**
   - Push notifications (future)
   - In-app status changes
   - Visual indicators

3. **Provider Online Indicators**
   - Real-time availability
   - Last seen timestamps
   - Active status display

4. **Job Broadcast Notifications**
   - New job alerts
   - Job claimed notifications
   - Status change alerts

### PWA Behavior

1. **Add to Home Screen**
   - Web app manifest
   - Install prompts
   - Home screen icons

2. **Offline Caching**
   - Dashboard data cached
   - Job history available offline
   - Service worker implementation

3. **Background Sync**
   - Job updates sync when online
   - Status changes queued
   - Automatic retry

4. **Push Notifications** (Future-Compatible)
   - Job broadcast alerts
   - Status update notifications
   - Payment notifications

### Provider Mobile UX

1. **Quick Job Claiming**
   - One-tap claim
   - Minimal confirmation
   - Instant feedback

2. **Minimal Typing**
   - Pre-filled forms
   - Voice input (future)
   - Quick status updates

3. **Large "Go Online" Toggle**
   - Prominent placement
   - Clear visual state
   - Instant feedback

4. **Route Navigation Shortcut**
   - Open in maps app
   - Navigation integration
   - ETA calculation

### Customer Mobile UX

1. **Real-Time "Pro En Route" Tracker**
   - Live status display
   - ETA countdown
   - Map view (future)

2. **ETA Visibility**
   - Clear time estimates
   - Updates in real-time
   - Visual indicators

3. **Clear Job Statuses**
   - Large status cards
   - Color-coded states
   - Progress indicators

4. **Mobile Map Integration** (Future)
   - Pro location tracking
   - Route visualization
   - Arrival notifications

### Mobile Aesthetics

1. **High-Contrast UI**
   - Readable in sunlight
   - Clear visual hierarchy
   - Accessible colors

2. **Performance-Optimized**
   - Fast load times
   - Smooth animations
   - Efficient rendering

3. **Minimal JS**
   - Server-rendered HTML
   - Progressive enhancement
   - Lightweight interactions

4. **Motion Used Sparingly**
   - Meaningful animations only
   - Performance-conscious
   - Accessibility-aware

### Mobile Behavior Must Reinforce

- Urgency
- Speed
- Live activity
- Clarity under stress

### Mobile Routes (To Be Implemented)

```
/mobile/dashboard - Mobile-optimized dashboard
/mobile/jobs/{id}/track - Mobile job tracking
/mobile/pro/claim/{id} - Quick claim interface
/mobile/pro/status/{id} - Quick status update
```

---

## Implementation Status

### ✅ Completed

1. **Marketplace Core**
   - Job dispatch system
   - Provider matching
   - Status tracking
   - Provider registration

2. **Content System**
   - Marketplace-native language
   - Human-authored templates
   - SEO-optimized pages

3. **Frontend Aesthetics**
   - Anti-slop design
   - Brand palette
   - Custom fonts
   - Industrial aesthetic

4. **Link Integrity**
   - Valid routes only
   - Route helpers used
   - No fictional URLs

5. **Provider System**
   - Registration
   - Authentication
   - Dashboard
   - Job claiming

### ⏳ Pending Implementation

1. **Admin Panel**
   - Provider verification interface
   - Job oversight dashboard
   - Dispute handling
   - Analytics dashboard

2. **Payment System**
   - Stripe Connect integration
   - Provider onboarding flow
   - Payment processing
   - Earnings dashboard

3. **Mobile/PWA**
   - Mobile-first layouts
   - PWA manifest
   - Service worker
   - Real-time updates

4. **Real-Time Features**
   - WebSocket integration
   - Push notifications
   - Live location tracking
   - Background sync

---

## Compliance Checklist

### Global Kernel Compliance

- ✅ Marketplace identity maintained
- ✅ Content follows voice rules
- ✅ UI follows aesthetic rules
- ✅ Links map to valid routes
- ✅ Backend follows lifecycle
- ✅ SEO maintained
- ✅ Marketplace DNA preserved

### Admin Kernel Compliance

- ⏳ Admin panel implemented
- ⏳ Provider verification flow
- ⏳ Job oversight interface
- ⏳ Dispute handling
- ⏳ Analytics dashboard

### Payment Kernel Compliance

- ⏳ Stripe Connect integrated
- ⏳ Provider onboarding flow
- ⏳ Payment processing
- ⏳ Earnings tracking
- ⏳ Platform fee calculation

### Mobile Kernel Compliance

- ⏳ Mobile-first layouts
- ⏳ PWA manifest
- ⏳ Service worker
- ⏳ Real-time updates
- ⏳ Push notifications (future)

---

## Enforcement

**All code, content, UI, and features must comply with this global kernel.**

Violations will result in:
1. Code rejection
2. Content rewrite
3. UI redesign
4. Feature removal

**This kernel is non-negotiable.**

---

## Version History

- **v1.0** (2024-11-22) - Initial global kernel definition
  - Fused all existing kernels
  - Added admin kernel
  - Added payment kernel
  - Added mobile/PWA kernel

---

*This document governs all development, content creation, and feature implementation for Unloqit.*

