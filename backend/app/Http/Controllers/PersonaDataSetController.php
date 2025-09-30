<?php

namespace App\Http\Controllers;

class PersonaDataSetController extends Controller
{

    public function getSamplePosts(string $influencer, ?string $field = null): array
    {
        $allSamples = [
            'Finance' => [
                'Oana Labes' => [
                    // Example 1…
                    'Example 1:' . "\n" .
                        "Most CEOs focus on the P&L.\n\n" .
                        "That's a big mistake.\n\n" .
                        "A company can be profitable today and still fail to scale, attract investors, or create long‑term shareholder value.\n\n" .
                        "Why?\n\n" .
                        "Because CEOs focus on earnings instead of cash flow strategy.\n\n" .
                        "Download my viral one page guide to analyzing a cash flow statement: https://lnkd.in/e2JXiUK6\n\n" .
                        "Without a plan to generate, allocate, and invest cash, you risk:\n" .
                        "↳ Chasing revenue instead of high‑ROI investments\n" .
                        "↳ Wasting capital on low‑impact initiatives\n" .
                        "↳ Carrying debt that weakens financial flexibility\n" .
                        "↳ Failing to attract the right capital at the right time\n" .
                        "↳ Missing opportunities to scale sustainably\n\n" .
                        "📌That’s why the Cash Flow Statement is the most important financial report in your business.\n\n" .
                        "Here’s how it actually works—and how to master it.\n\n" .
                        "1. Operating Activities = The Cash Engine\n" .
                        "This is the heartbeat of your business—where cash is created or destroyed in daily operations.\n\n" .
                        "Where cash comes from:\n" .
                        "↳ Sales collected in cash\n" .
                        "↳ Payments received on past receivables\n" .
                        "↳ Interest and dividends received\n\n" .
                        "Where cash goes:\n" .
                        "↳ Payments to suppliers and employees\n" .
                        "↳ Interest on debt\n" .
                        "↳ Taxes\n\n" .
                        "How to master it:\n" .
                        "↳ Optimize your cash conversion cycle.\n" .
                        "↳ Use sensitivity analysis to stress‑test your plans.\n\n" .
                        "2. Investing Activities = The Growth Playbook\n" .
                        "This is where strategy meets execution—how you deploy cash to drive enterprise value.\n\n" .
                        "Where cash comes from:\n" .
                        "↳ Selling equipment or investments\n" .
                        "↳ Loan repayments from others\n\n" .
                        "Where cash goes:\n" .
                        "↳ Buying property, equipment, or investments\n" .
                        "↳ Lending cash to others\n\n" .
                        "How to master it:\n" .
                        "↳ Prioritize investments using NPV and IRR.\n" .
                        "↳ Align investments with sustainable operating cash flow growth\n\n" .
                        "3. Financing Activities = The Capital Structure\n" .
                        "This determines how you fund your expansion—through debt or equity.\n\n" .
                        "Where cash comes from:\n" .
                        "↳ Issuing stock\n" .
                        "↳ Taking on new debt\n\n" .
                        "Where cash goes:\n" .
                        "↳ Paying dividends\n" .
                        "↳ Buying back stock\n" .
                        "↳ Paying off debt\n\n" .
                        "How to master it:\n" .
                        "↳ Optimize your capital structure.\n" .
                        "↳ Align dividend and stock repurchase policies.\n\n" .
                        "The Takeaway:\n\n" .
                        "Most CEOs focus on profit first.\n" .
                        "But real financial intelligence starts with cash flow strategy.\n" .
                        "Because the businesses that win aren’t just profitable—they leverage their cash to maximize enterprise value.\n\n" .
                        "📌 Join my CEO Financial Intelligence Program to master strategic capital allocation and much more: https://bit.ly/3ZCI0kr\n" .
                        "📌 Enroll in my 5* on‑demand video courses: https://bit.ly/3RlTCDD and get my popular infographics: https://bit.ly/3K2B5Jc\n" .
                        "📌 Transform your strategic financial planning, forecasting and reporting with Financiario.\n\n" .
                        "Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    // Example 2…
                    'Example 2:' . "\n" .
                        "Most CEOs and CFOs chase EBITDA growth like it’s the holy grail.\n\n" .
                        "But here’s the truth: You can manipulate EBITDA... You cannot manipulate Cash Flow.\n\n" .
                        "Download my viral one page guide to analyzing a cash flow statement and never be confused again: https://lnkd.in/e2JXiUK6\n\n" .
                        "Here’s the problem:\n" .
                        "EBITDA is an incomplete measure. It ignores interest, taxes, depreciation, and amortization—conveniently removing real expenses that impact your future.\n\n" .
                        "And with a few accounting tricks, you can even make EBITDA look stronger than reality:\n" .
                        "✓ Capitalizing expenses instead of expensing them\n" .
                        "✓ Excluding stock-based compensation as if it’s not a real cost\n" .
                        "✓ Reclassifying recurring costs as “one-time” or “non-core”\n" .
                        "✓ Aggressive revenue recognition before the cash actually arrives\n\n" .
                        "Pretty concerning, don't you think?\n\n" .
                        "And the result?\n" .
                        "A high EBITDA on paper while your business quietly runs out of cash.\n\n" .
                        "Here’s the Reality:\n" .
                        "Cash flow is different. It tells you what EBITDA won’t:\n" .
                        "✓ Can you pay your bills?\n" .
                        "✓ Can you invest in growth?\n" .
                        "✓ Where is your cash coming from?\n" .
                        "✓ Can you fund your debt repayments?\n\n" .
                        "You can report high EBITDA and still run out of money.\n" .
                        "But strong cash flow means you’re in control.\n\n" .
                        "The Takeaway?\n" .
                        "Stop obsessing over EBITDA. Start optimizing cash flow, liquidity, and capital allocation.\n\n" .
                        "Because at the end of the day, cash flow pays the bills, fuels growth and drives enterprise value.\n\n" .
                        "📌 Make 2025 your best year yet and learn to master financial leadership↴\n\n" .
                        "▷ Enroll in my 5* on-demand video courses: https://bit.ly/3RlTCDD\n" .
                        "▷ Join the waitlist for my 6-week Financial Intelligence Program: https://bit.ly/3ZCI0kr\n" .
                        "▷ Get my popular infographics: https://bit.ly/3K2B5Jc\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    // NEW Example 3…
                    'Example 3:' . "\n" .
                        "Not all Profits are created Equal.\n\n" .
                        "But here’s the problem.\n" .
                        "Most executives can’t really tell them apart.\n\n" .
                        "I see it all the time—leaders celebrating a “profitable” business…\n\n" .
                        "All while burning cash, missing targets, and failing to scale.\n\n" .
                        "Why? Because they don’t know which profit actually matters.\n\n" .
                        "▷ Get my free one-pager and learn to analyze a balance sheet in 10 steps: https://bit.ly/3QsGLyV\n\n" .
                        "Here’s how to make each kind of profit work for you →\n\n" .
                        "1. Gross Profit → Optimize Pricing & Production\n\n" .
                        "✓ Use it to assess pricing strategy—declining gross profit? Prices are too low or costs are rising.\n\n" .
                        "✓ Benchmark production efficiency—are labor and materials being used effectively?\n\n" .
                        "✓ If margins shrink, investigate direct costs (COGS) before touching overhead.\n\n" .
                        "2. Contribution Margin → Improve Scalability\n\n" .
                        "✓ Measures profitability before fixed costs—low contribution margin? You’ll struggle to scale.\n\n" .
                        "✓ Helps decide pricing and product mix—focus on high-margin products.\n\n" .
                        "✓ Use it for breakeven analysis—know exactly how much revenue you need to cover fixed costs.\n\n" .
                        "3. Operating Profit → Control Overhead & Runway\n\n" .
                        "✓ The best indicator of core business health—can your revenue sustain operating expenses?\n\n" .
                        "✓ Use it to assess overhead spending—high costs mean inefficiencies in SG&A or salaries.\n\n" .
                        "✓ If weak, focus on cost control without killing growth.\n\n" .
                        "4. Net Profit → Cash Flow & Long-Term Viability\n\n" .
                        "✓ The final score—tracks total profitability after all expenses, interest, and taxes.\n\n" .
                        "✓ Use it to assess financing needs—low or negative net profit? Your cash flow is at risk.\n\n" .
                        "✓ If declining, don’t just cut costs blindly —check tax strategy, debt structure, and Gross Profit first.\n\n" .
                        "The Takeaway:\n\n" .
                        "Profit isn’t a single metric.\n" .
                        "It’s a toolbox.\n\n" .
                        "Use the right tool at the right time, and watch your bottom line grow.\n\n" .
                        "Make 2025 your best year yet and learn to master financial leadership↴\n\n" .
                        "▷ Get my popular infographics: https://bit.ly/3K2B5Jc\n" .
                        "▷ Get my 5* on-demand video courses: https://bit.ly/3RlTCDD\n" .
                        "▷ Join me for a free strategic cash flow webinar: https://bit.ly/49n7Lqh\n" .
                        "▷ Apply for my 6-week Financial Intelligence Program: https://bit.ly/3ZCI0kr\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    'Example 4:' . "\n\n" .
                        "Finance is confusing.\n\n" .
                        "So here are **20 Frequently Confused Finance Topics to Know.**\n\n" .
                        "Learn the differences and how to use them correctly.\n\n" .
                        "➡️ Download my ultimate guide to analyzing a Balance Sheet and never miss a red flag again: https://bit.ly/4jTnzI9\n\n" .
                        "1. Gross Margin vs. Net Margin\n" .
                        "2. Cash Flow vs. Profit\n" .
                        "3. EPS Growth vs. Revenue Growth\n" .
                        "4. Debt-to-Equity vs. Interest Coverage\n" .
                        "5. ROI vs. IRR\n" .
                        "6. Book Value vs. Market Value\n" .
                        "7. Operating Expenses vs. Capital Expenditures\n" .
                        "8. Fixed Costs vs. Variable Costs\n" .
                        "9. Current Ratio vs. Quick Ratio\n" .
                        "10. P/E Ratio vs. P/B Ratio\n" .
                        "11. Accrual Accounting vs. Cash Accounting\n" .
                        "12. Economic Profit vs. Accounting Profit\n" .
                        "13. Operating Leverage vs. Financial Leverage\n" .
                        "14. Amortization vs. Depreciation\n" .
                        "15. Marginal Cost vs. Average Cost\n" .
                        "16. Liquidity vs. Solvency\n" .
                        "17. Cash Conversion Cycle vs. Operating Cycle\n" .
                        "18. Forward P/E vs. Trailing P/E\n" .
                        "19. Contribution Margin vs. Operating Margin\n" .
                        "20. Market Capitalization vs. Enterprise Value\n\n" .
                        "Share this to help your network!\n\n" .
                        "Let me know below what other topics were new to you.\n\n" .
                        "And which other ones you'd add to the list. ↴\n\n" .
                        "📌 Make 2025 your best year yet and learn to master financial leadership↴\n\n" .
                        "▷ Enroll in my 5* on-demand video courses: https://bit.ly/3RlTCDD\n\n" .
                        "▷ Join the waitlist for my 6-week Financial Intelligence Program: https://bit.ly/3ZCI0kr\n\n" .
                        "▷ Get my popular infographics: https://bit.ly/3K2B5Jc\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    // Example 5 (post 6)…
                    'Example 5:' . "\n\n" .
                        "**How to analyze an income statement in 10 simple steps**\n\n" .
                        "Get your copy of the ultimate guide. ↴\n\n" .
                        "(bookmark for later)\n\n" .
                        "➡️ Want this in a full-resolution PDF? Like & comment, then download it here: https://bit.ly/3QsGLyV\n\n" .
                        "1️⃣ **Revenue Trends**\n\n" .
                        "Is revenue growing consistently, or is it unpredictable?\n\n" .
                        "A business overly dependent on a handful of customers or products is vulnerable to market shifts.\n\n" .
                        "2️⃣ **Cost of Revenue & Gross Profit**\n\n" .
                        "If costs are rising faster than revenue, profitability is at risk.\n\n" .
                        "Compare costs over time to spot trends that could erode margins.\n\n" .
                        "3️⃣ **Operating Expenses & Efficiency**\n\n" .
                        "A company can be profitable but still wasteful.\n\n" .
                        "If operating costs are climbing without a proportional increase in revenue, that’s a red flag.\n\n" .
                        "4️⃣ **Marketing & Customer Acquisition**\n\n" .
                        "Look for signs of overspending on customer acquisition without improving retention.\n\n" .
                        "Sustainable businesses invest in both attracting and keeping customers.\n\n" .
                        "5️⃣ **Profitability & Sustainability**\n\n" .
                        "Profit growth should be supported by efficient cost management, not just higher sales.\n\n" .
                        "If a company is only profitable because it cut costs drastically, is that sustainable?\n\n" .
                        "6️⃣ **Debt & Interest Expenses**\n\n" .
                        "If interest expenses are rising, debt may be growing faster than the business.\n\n" .
                        "Can the company easily cover its debt obligations, or is it at risk of financial strain?\n\n" .
                        "7️⃣ **Tax & Non-Operating Income**\n\n" .
                        "One-time gains from asset sales, tax credits, or write-offs can distort financial results.\n\n" .
                        "Is reported profit coming from core business operations or from accounting adjustments?\n\n" .
                        "8️⃣ **Net Income & Profit Retention**\n\n" .
                        "High reported profits don’t always mean the company is in good shape.\n\n" .
                        "If earnings are strong, but cash flow isn’t, is the business truly making money?\n\n" .
                        "Look at how much profit is reinvested versus distributed—excessive payouts may limit future growth.\n\n" .
                        "9️⃣ **Cash Flow Alignment**\n\n" .
                        "A healthy income statement should be backed by a strong cash position.\n\n" .
                        "If revenue is up, but cash flow is weak, the company may struggle to cover expenses.\n\n" .
                        "Strong earnings mean nothing if cash isn’t flowing in consistently.\n\n" .
                        "🔟 **Long-Term Trends & Industry Comparisons**\n\n" .
                        "One good quarter doesn’t define success—look at performance over several periods.\n\n" .
                        "Compare financials with industry peers to see if the company is keeping up or falling behind.\n\n" .
                        "Sustainable growth comes from consistent execution, not one-time wins.\n\n" .
                        "**The Bottom Line:**\n\n" .
                        "An income statement is more than just revenue and profit.\n\n" .
                        "It tells the story of how well a business manages growth, expenses, and long-term sustainability.\n\n" .
                        "Master these 10 steps, and you’ll never miss a red flag again.\n\n" .
                        "--------\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. Follow me, MBA, CPA for more insights on financial leadership.",

                    // Example 6 (post 7)…
                    'Example 6:' . "\n\n" .
                        "**Most companies are stuck in the budget cycle.**\n" .
                        "And their CEOs struggle to see beyond the next quarter.\n\n" .
                        "Here's the problem:\n\n" .
                        "Budgets don’t adapt.\n" .
                        "Forecasts don’t go far enough.\n\n" .
                        "And that’s why so many companies fail to plan for the future.\n\n" .
                        "➡️ Download my 30-point cash flow checklist and start making better strategic business decisions: https://bit.ly/4fp2eUr\n\n" .
                        "**The Budget Cycle Trap:**\n\n" .
                        "📌 Budgets set fixed targets based on outdated assumptions.\n\n" .
                        "Market shifts? Unexpected costs? Emerging opportunities? The budget doesn’t change.\n\n" .
                        "📌 Forecasts only adjust within the budget cycle.\n\n" .
                        "They update based on actuals, but they rarely extend beyond year-end.\n\n" .
                        "📌 CEOs get stuck making short-term decisions.\n\n" .
                        "Instead of allocating capital strategically, they’re stuck explaining why numbers don’t match the plan.\n\n" .
                        "**The Solution? Rolling Forecasts.**\n\n" .
                        "📌 Instead of being locked into last year’s budget, rolling forecasts adjust dynamically.\n\n" .
                        "📌 Instead of stopping at year-end, rolling forecasts extend 12+ months forward (or 60 months like Financiario does for mid-market companies).\n\n" .
                        "📌 Instead of reacting, CEOs anticipate, adapt, and make capital decisions with real business insight.\n\n" .
                        "The best companies break free from the budget cycle.\n" .
                        "They use rolling forecasts to stay ahead—not just catch up.\n\n" .
                        "**Key Lesson:**\n\n" .
                        "If you're still relying on static budgets and short-term forecasts…\n" .
                        "You’re already behind.\n\n" .
                        "Rolling forecasts give CEOs the long-term vision to lead—not just manage.\n" .
                        "The smartest companies don’t just budget or forecast.\n" .
                        "They navigate.\n\n" .
                        "↳ Transform your financial acumen as a leader in only 6 weeks – live program, spots are limited, join the waitlist: https://bit.ly/3ZCI0kr\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    // Example 7 (post 8)…
                    'Example 7:' . "\n\n" .
                        "**How to Analyze a Balance Sheet in 10 Simple Steps**\n" .
                        "Get your copy of the ultimate guide. ↴\n\n" .
                        "(bookmark for later)\n\n" .
                        "➡️ Want to get this in full-resolution pdf? Like and comment to share with your network.\n\n" .
                        "Then get it here: https://bit.ly/4jTnzI9\n\n" .
                        "1️⃣ **Cash Balances & Liquidity**\n\n" .
                        "Start by reviewing cash and short-term investments.\n\n" .
                        "Healthy cash reserves provide a cushion for covering operations and short-term obligations.\n\n" .
                        "2️⃣ **Current vs. Non-Current Assets**\n\n" .
                        "Compare current (short-term) and non-current (long-term) assets.\n\n" .
                        "A low proportion of liquid assets could indicate difficulty covering short-term needs.\n\n" .
                        "3️⃣ **Accounts Receivable & Collection Efficiency**\n\n" .
                        "Analyze receivables for rising balances or slow collections which could indicate collectability issues.\n\n" .
                        "Review Days Sales Outstanding (DSO) for collection efficiency and compare trends and benchmarks.\n\n" .
                        "4️⃣ **Inventory Levels & Operational Efficiency**\n\n" .
                        "Excess inventory may signal overproduction; low inventory could mean supply issues.\n\n" .
                        "Check Inventory Turnover to assess inventory management.\n\n" .
                        "5️⃣ **PP&E Growth & Investment Quality**\n\n" .
                        "Growth in Property, Plant & Equipment (PP&E) may indicate expansion.\n\n" .
                        "Evaluate if investments align with revenue growth to avoid poor capital allocation.\n\n" .
                        "6️⃣ **Short-Term Debt & Liquidity Risk**\n\n" .
                        "Rising short-term debt can signal cash flow strain.\n\n" .
                        "Compare debt levels with cash flow trends to assess repayment capacity.\n\n" .
                        "7️⃣ **Deferred Liabilities & Long-Term Obligations**\n\n" .
                        "Examine deferred tax liabilities and pension obligations.\n\n" .
                        "Understand their impact on future cash flows and long-term planning.\n\n" .
                        "8️⃣ **Equity Changes: Issuances & Buybacks**\n\n" .
                        "Issuing shares raises capital but dilutes ownership.\n\n" .
                        "Buybacks may signal confidence but could be unsustainable depending on how they are funded.\n\n" .
                        "9️⃣ **Leverage & Debt Coverage**\n\n" .
                        "Evaluate leverage through Debt-to-Equity and Debt Service Coverage Ratios.\n\n" .
                        "Ensure debt is manageable with operating cash flows.\n\n" .
                        "🔟 **Retained Earnings & Capital Structure**\n\n" .
                        "Growing retained earnings indicate reinvestment of profits.\n\n" .
                        "Stagnant or negative retained earnings may signal poor performance or excessive dividends.\n\n" .
                        "Align debt and equity mix with short- and long-term business goals.\n\n" .
                        "Want to learn more? Accelerate your career and grow your business with my strategic finance resources.\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    // Example 8 (post 9)…
                    'Example 8:' . "\n\n" .
                        "**What gets measured gets managed.**\n" .
                        "But here's the problem.\n\n" .
                        "Most CEOs are measuring the wrong things.\n\n" .
                        "They fixate on revenue, celebrate EBITDA, and overlook the metrics that actually drive sustainable growth.\n\n" .
                        "That’s why they’re stuck—scaling slower, losing customers, and wondering why their teams are disengaged.\n\n" .
                        "The best CEOs measure their business through 4 critical lenses—because what you don’t measure, you can’t improve.\n\n" .
                        "📌 Want a pdf copy? Like, comment and share. Then download here: https://bit.ly/3CPBka2\n\n" .
                        "1. **Financial Perspective:**\n\n" .
                        "Profit is a byproduct.\n" .
                        "Efficiency is the engine.\n\n" .
                        "The question: Can your business sustain itself and scale profitably?\n\n" .
                        "→ CEOs who track cash flow and efficiency build businesses that survive and scale.\n\n" .
                        "2. **Customer Perspective:**\n\n" .
                        "Acquisition is expensive.\n" .
                        "Retention is profitable.\n\n" .
                        "Yet most CEOs track sales but ignore churn. Big mistake.\n\n" .
                        "→ Retention is a lagging indicator of your entire business strategy. Ignore it at your peril.\n\n" .
                        "3. **Employee Perspective:**\n\n" .
                        "Happy teams perform.\n" .
                        "Disengaged teams quit—or worse, stay and underperform.\n\n" .
                        "But most CEOs only track headcount and payroll. They miss the real signals.\n\n" .
                        "→ CEOs who measure engagement build cultures that outperform competitors.\n\n" .
                        "4. **Skills & Innovation Perspective:**\n\n" .
                        "Innovation isn’t a buzzword—it’s a KPI.\n\n" .
                        "But if you’re not tracking how fast your team learns, adopts, and grows, you’re already falling behind.\n\n" .
                        "→ CEOs who track innovation win the future. Period.\n\n" .
                        "**The Takeaway:**\n\n" .
                        "What gets measured gets managed. What gets ignored gets worse.\n\n" .
                        "The best CEOs don’t track everything—they track the right things.\n" .
                        "Finance. Customers. Employees. Innovation.\n\n" .
                        "Four perspectives.\n" .
                        "One complete view of your business.\n" .
                        "Zero guesswork.\n\n" .
                        "Any other KPIs you’d add to the list?\n\n" .
                        "📌 Want to 10x your financial leadership?\n\n" .
                        "▷▷▷ Watch my strategic cash flow webinar (it’s free): https://bit.ly/49n7Lqh\n" .
                        "▷▷▷ Learn with my 5* on-demand video courses: https://bit.ly/3RlTCDD\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",

                    // Example 9 (post 10)…
                    'Example 9:' . "\n\n" .
                        "**Every business decision is a capital allocation decision.**\n" .
                        "Every business challenge is a capital allocation challenge.\n" .
                        "But here’s the problem:\n\n" .
                        "Most leaders don’t treat it that way.\n\n" .
                        "They chase growth. They chase revenue. They chase activity.\n\n" .
                        "But they miss the real game: Maximizing shareholder value through smarter capital allocation.\n\n" .
                        "↳ Download my 30-point cash flow checklist and start making smarter business decisions today: https://bit.ly/4fp2eUr\n\n" .
                        "Let’s break it down:\n\n" .
                        "Every hire is capital allocation.\n" .
                        "Every marketing campaign is capital allocation.\n" .
                        "Every acquisition is capital allocation.\n\n" .
                        "The only question that matters: Is this the best use of capital?\n\n" .
                        "Here’s why most businesses fail to create real value:\n\n" .
                        "They rely on vanity metrics like EBITDA instead of cash flow.\n" .
                        "They invest in projects without a clear return on invested capital (ROIC).\n" .
                        "They ignore opportunity costs and spread capital too thin.\n\n" .
                        "So, how do you fix it?\n\n" .
                        "1. **Prioritize cash flow over EBITDA**\n\n" .
                        "EBITDA is an illusion. Cash flow is reality.\n\n" .
                        "Measure success by how much cash every dollar of investment returns.\n\n" .
                        "2. **Set a hurdle rate—and enforce it.**\n\n" .
                        "If a project can’t beat your cost of capital, say no. Discipline drives results.\n\n" .
                        "3. **Track return on invested capital (ROIC)**\n\n" .
                        "ROIC is the ultimate test: Are you creating value or destroying it?\n\n" .
                        "4. **Reallocate relentlessly**\n\n" .
                        "The best leaders cut losses fast.\n" .
                        "And they double down where capital earns the highest return.\n\n" .
                        "**Here's the bottom line:**\n\n" .
                        "Capital allocation is the ultimate strategy.\n\n" .
                        "Get it wrong, and no amount of cost-cutting will save you.\n" .
                        "Get it right, and shareholder value takes care of itself.\n\n" .
                        "Want to learn more?\n\n" .
                        "↳ Transform your knowledge and skills with my 5* on-demand video courses: https://bit.ly/3RlTCDD\n\n" .
                        "↳ Connect your P&L & your valuation with my innovative 16-in-1 tool: https://bit.ly/466yurg\n\n" .
                        "♻️ Like, Comment, Repost if this was helpful. And follow me, MBA, CPA for more insights on financial leadership.",
                ],

                'Josh Aharonoff' => [
                    'Example 1:' . "\n" .
                        "**Stop ignoring your Balance Sheet!** 🧮\n" .
                        "If you're forecasting without ALL THREE financial statements, you're making a HUGE mistake.\n\n" .
                        "I see it all the time...\n\n" .
                        "Finance professionals create forecasts with just a Profit & Loss, and maybe a manually calculated cash flow statement...\n\n" .
                        "But they leave out the Balance Sheet.\n\n" .
                        "**This is like trying to drive a car with just the speedometer but no gas gauge or GPS.** 🚗\n\n" .
                        "You might know how fast you're going, but you have no idea how much fuel you have left or where you're headed!\n\n" .
                        "Let’s break it down 👇\n\n" .
                        "➡️ **Why you NEED all 3 statements in your forecast**\n\n" .
                        "Forecasting ALL THREE financial statements gives you context that a standalone P&L simply cannot provide.\n\n" .
                        "…\n\n" .
                        "**Do you include all 3 statements in your financial models?**",

                    'Example 2:' . "\n" .
                        "**Excel’s Copilot just released some CRAZY new features**\n\n" .
                        "So I spent the entire weekend testing it…and I'm pretty blown away.\n\n" .
                        "➡️ **WHAT IS COPILOT?**\n\n" .
                        "Think of Copilot as an AI chatbot right in your Excel file…\n\n" .
                        "➡️ **HOW TO GET STARTED**\n\n" .
                        "…\n\n" .
                        "**Is Copilot part of your Excel workflow yet?**",

                    'Example 3:' . "\n" .
                        "**32 BEST Excel Keyboard Shortcuts** ⌨️\n" .
                        "Memorize these to 10x your productivity 🤓\n\n" .
                        "👉 **Grab the high res PDF for FREE** in the next 48 hours: https://lnkd.in/esdQe3bk\n\n" .
                        "⚡ **QUICK EDITS →** use these shortcuts for quick actions\n" .
                        "✅ F2 – edit cells, and toggle cell indicator mode\n" .
                        "✅ F4 – fix references\n" .
                        "…\n\n" .
                        "**Did you know them all?**\n" .
                        "And did I miss any of your favorites?",

                    'Example 4:' . "\n\n" .
                        "**32 BEST Excel Keyboard Shortcuts** ⌨️\n\n" .
                        "Memorize these to 10x your productivity 🤓\n\n" .
                        "👉 Grab the high res PDF for FREE in the next 48 hours\n" .
                        "🔗 https://lnkd.in/esdQe3bk\n\n" .
                        "⚡ QUICK EDITS → use these shortcuts for quick actions\n\n" .
                        "⬜ F2 - edit cells, and toggle cell indicator mode\n" .
                        "⬜ F4 - fix references\n" .
                        "…\n\n" .
                        "**Did you know them all?**\n" .
                        "And did I miss any of your favorites?",

                    // Example 5…
                    'Example 5:' . "\n\n" .
                        "An entire college Finance & Accounting 101 course fits in one Excel file\n" .
                        "But you don’t have to pay college tuition fees to get it\n\n" .
                        "📍 Today…I’m giving it to you for free\n" .
                        "https://lnkd.in/ea2-DsUG\n\n" .
                        "📣 Like & share this post to help me spread financial literacy 📣\n\n" .
                        "Here’s what’s included:\n\n" .
                        "1️⃣ The Fundamentals\n\n" .
                        "➡️ The Accounting Equation\n" .
                        "Assets = Liabilities + Owners Equity.\n\n" .
                        "➡️ Cash vs Accrual Accounting\n\n" .
                        "➡️ Debits & Credits\n\n" .
                        "➡️ Reporting Standards\n\n" .
                        "➡️ An overview of the Financial Statements\n\n" .
                        "2️⃣ An in depth view on each financial statement\n\n" .
                        "3️⃣ Key Ratios & KPIs\n\n" .
                        "I hope that the information in this template gives you the ROI that it gave me!",

                    // Example 6…
                    'Example 6:' . "\n\n" .
                        "🧪The Periodic Table of EXCEL FUNCTIONS\n" .
                        "👨‍🔬 Where Spreadsheets Meets Science!\n\n" .
                        "I nearly flunked chemistry in high-school...\n" .
                        "But always exceled (pun intended) while working in a spreadsheet\n\n" .
                        "Study these elements to become an Excel Scientist 👨‍⚕️\n\n" .
                        "⚗️Basic Math “Elements”:\n" .
                        "- Su (SUM): Adds up all numbers\n" .
                        "- Av (AVERAGE): Calculates mean\n\n" .
                        "🧬Logical “Elements”:\n" .
                        "- If (IF): Makes decisions\n" .
                        "- An (AND): All conditions true\n\n" .
                        "🔬Lookup & Reference:\n" .
                        "- Vl (VLOOKUP)\n" .
                        "- Xl (XLOOKUP)\n\n" .
                        "⚡Financial:\n" .
                        "- Pv (PV)\n" .
                        "- Fv (FV)\n\n" .
                        "⏱️Date & Time:\n" .
                        "- Td (TODAY)\n" .
                        "- Wk (WEEKDAY)\n\n" .
                        "📊Statistical:\n" .
                        "- Sd (STDEV)\n" .
                        "- Md (MEDIAN)\n\n" .
                        "🧫Text Functions:\n" .
                        "- Tx (TEXT)\n" .
                        "- Cn (CONCAT)\n\n" .
                        "— Which element is your favorite? —",

                    // Example 7…
                    'Example 7:' . "\n\n" .
                        "**This is the MOST POPULAR function in Excel 😱**\n\n" .
                        "Of course, I’m talking about the IF function…\n\n" .
                        "🟰IF: IF(condition, value_if_true, value_if_false)\n\n" .
                        "🟰IFERROR: Returns a value instead of an error\n\n" .
                        "🟰IFNA: Returns a value on #N/A only\n\n" .
                        "Advanced IFs:\n" .
                        "- SUMIFS\n" .
                        "- COUNTIFS\n" .
                        "- AVERAGEIFS\n\n" .
                        "Modifiers:\n" .
                        "- AND\n" .
                        "- OR\n" .
                        "- NOT\n\n" .
                        "TRUE/FALSE helpers:\n" .
                        "- ISBLANK\n" .
                        "- ISTEXT\n" .
                        "- ISDATE\n\n" .
                        "**Which IF variant powers your model?**",

                    // Example 8…
                    'Example 8:' . "\n\n" .
                        "**6 Excel Functions you NEED to Know**\n\n" .
                        "1️⃣ =IF — Simple conditional logic\n" .
                        "2️⃣ =SUMIFS — Conditional summing\n" .
                        "3️⃣ INDEX / MATCH & XLOOKUP — Flexible lookups\n" .
                        "4️⃣ =SUMPRODUCT — Array math & logic\n" .
                        "5️⃣ =EOMONTH — Month‑end dates\n" .
                        "6️⃣ =LET — Variables inside formulas\n\n" .
                        "Which one will you master next?",

                    // Example 9…
                    'Example 9:' . "\n\n" .
                        "**Big 4 Accounting**\n" .
                        "The path to TAKE or the path to AVOID?\n\n" .
                        "➡️ WHAT ARE THE BIG 4?\n" .
                        "- Ernst & Young\n" .
                        "- Deloitte\n" .
                        "- KPMG\n" .
                        "- PwC\n\n" .
                        "➡️ PROS:\n" .
                        "✅ Brand recognition\n" .
                        "✅ Strong benefits\n\n" .
                        "➡️ CONS:\n" .
                        "👎 Long hours\n" .
                        "👎 Pigeon‑holing\n\n" .
                        "**Is Big 4 your launchpad—or dead end?**",

                    // Example 10…
                    'Example 10:' . "\n\n" .
                        "**Your business is sick…but you had no idea 🤒**\n\n" .
                        "This Financial Health Dashboard will keep you in check👇\n\n" .
                        "➡️ OVERALL STATUS: Red = fix now, Green = on track\n\n" .
                        "➡️ PROFITABILITY:\n" .
                        "• Gross Margin = (Revenue - COGS)/Revenue\n" .
                        "• Net Margin = Operating Income/Revenue\n" .
                        "• EBITDA = Earnings before interest, tax, depreciation & amortization\n\n" .
                        "➡️ CASH METRICS:\n" .
                        "• Working Capital = Current Assets - Current Liabilities\n" .
                        "• Cash Flow Movements\n" .
                        "• Cash‑Out Date\n\n" .
                        "➡️ SAAS METRICS:\n" .
                        "• ARR = Annual Recurring Revenue\n" .
                        "• CAC = Customer Acquisition Cost\n\n" .
                        "**Which metric do you track daily?**",
                ],
                'Graham Stephan' => [
                    // Post 1
                    'Example 1:' . "\n" .
                        "Questions people ask me all the time:\n\n" .
                        "- **Why are rents skyrocketing while my salary is staying the same?**  \n" .
                        "- **Are corporations buying up all the houses?**  \n" .
                        "- **Should I buy a house now or wait?**\n\n" .
                        "Which is why I thought I'd answer some of these questions.\n\n" .
                        "Right now, the housing market is more unpredictable than ever — rents keep rising, home prices remain sky‑high, and new construction just isn’t keeping up with demand.\n\n" .
                        "The US is short by over 4 million homes, but developers are largely focused on high‑end housing that brings more profit. Naturally, this creates a supply shortage for affordable homes, pushing prices even higher for both buyers and renters.\n\n" .
                        "Meanwhile, the costs of owning a home are also surging. My insurance premium increased by over 20% and property tax by over 5%, which naturally forces landlords to pass these costs onto the tenants.\n\n" .
                        "These are just some issues I discussed in my newsletter, where I break down the questions I get asked the most about the housing market in 2025.\n\n",

                    // Post 2
                    'Example 2:' . "\n" .
                        "The personal savings rate recently hit a **20‑year low**.\n\n" .
                        "And Social Security reserves are set to be completely depleted in the next eight years...\n\n" .
                        "Now, logically you would think that when Social Security takes your money from your taxes, it would add it into a special account that belongs to you.\n\n" .
                        "Makes sense right?  \n\n" .
                        "But it doesn't work like that.\n\n" .
                        "Instead, your money goes towards paying out current retirees. 💰➡️\n\n" .
                        "And by the time you retire, future workers will contribute their salary to pay you. ♾️➡️\n\n" .
                        "In a perfect system, incomes increase, payroll taxes go up, & the population grows enough to keep up with what Social Security needs to stay funded.\n\n" .
                        "But it's not a perfect system.\n\n" .
                        "- Population growth is declining. 📉  \n" .
                        "- Incomes are barely keeping up with inflation. 📈\n\n" .
                        "In fact, in less than 10 years, the real problem begins...\n\n" .
                        "**Want to take a guess in the comments?**  \n\n" .
                        "P.S. Mind *Smashing* the like button for me?? 😁",

                    // Post 3
                    'Example 3:' . "\n" .
                        "The most common argument against picking stocks is that you can make a lot of mistakes if you don't know what you're doing.\n\n" .
                        "But here's another idea: **You can do everything right and still lose.**\n\n" .
                        "You can read your Ben Graham, do your homework, read security analysis, go through the 10‑K reports and do everything you're supposed to.\n\n" .
                        "But one unlucky policy change or war or earthquake or just plain bad luck can change how your stock picks perform.\n\n" .
                        "The tricky thing about the market is that it's a **Keynesian beauty contest** — you don't get rich by just predicting the winners; you have to predict what other people will pick as winners.\n\n" .
                        "Even Warren Buffett—one of the smartest and most hard‑working investors of all time—relied on a heavy dose of luck early on.\n\n" .
                        "**Imagine this:** You’re 85 years old and someone shows up with a record of every investing decision you ever made, and compares that to simply buying and holding the S&P 500.\n\n" .
                        "Would you regret all that time spent studying stocks?\n\n" .
                        "If the answer is **yes**, you have no business picking stocks.",

                    'Example 4:' . "\n\n" .
                        "If you're itching to time the market, here's a cautionary tale:\n\n" .
                        "If you had invested \$10,000 in the S&P 500 and left it untouched, it would have grown to \$64,000 in 20 years.\n\n" .
                        "- Missing the best 10 days cuts your return to \$29,000.\n" .
                        "- Missing the best 20 days costs you \$47,000 in profit.\n" .
                        "- Missing the best 30 days means you just break even.\n" .
                        "- Missing the best 40 days, you actually lose money.\n\n" .
                        "The kicker? Those best days often happen during bear markets—when everyone is fearful.\n\n" .
                        "Investing and sitting tight is the simplest strategy—but the hardest to pull off.",

                    // Example 5
                    'Example 5:' . "\n\n" .
                        "Investing won't make you rich. Warren Buffett had “retired” even before he started Berkshire Hathaway.\n\n" .
                        "Hear me out:\n" .
                        "Sam Parr on David Perell's podcast said, “A 13‑year‑old should study what LeBron James was doing at 13, not what he’s doing today.”\n\n" .
                        "Beginners try to emulate Buffett today, but you really need to study **how he got his start**.\n\n" .
                        "By 1955, Buffett grew \$9,800 to \$127,000—a **67% annual return**—before he even managed outside money.\n\n" .
                        "**Key lesson:** For most young people, the fastest way to boost net worth is **invest in yourself**—your skills and income—before worrying about squeezing 1–2% more return on investments.",

                    // Example 6
                    'Example 6:' . "\n\n" .
                        "Many businesses give away freebies to attract customers.\n" .
                        "Alex Hormozi did the opposite—and sold 4,000 gym memberships.\n\n" .
                        "He charged \$500 per membership, but **refunded everything** if you lost 20 lbs in 6 months.\n\n" .
                        "✅ It attracted only people committed to results.\n" .
                        "✅ 78% hit their goal.\n\n" .
                        "They recouped refund costs by upselling supplements—yet most winners happily rolled their \$500 credit into more training.\n\n" .
                        "**Takeaway:** People don’t want freebies—they want results. Align incentives with outcomes, and you win twice.",

                    // Example 7
                    'Example 7:' . "\n\n" .
                        "Scammers are now faking loved‑one voices via AI. 77% of victims pay up.\n\n" .
                        "Robin got a 2 am call: “Mona is held hostage—\$750 or she dies!”\n" .
                        "Voice sounded identical. They paid—only to find Mom asleep.\n\n" .
                        "AI needs just 3 seconds of audio to clone a voice, and we post ourselves on social media all the time.\n\n" .
                        "FTC tips if you get such a call:\n" .
                        "1. Stall—say little so you don’t feed the clone.\n" .
                        "2. Verify location—call them back.\n" .
                        "3. Use a code‑word only you know.\n" .
                        "4. Block unknown numbers.\n\n" .
                        "**Have you heard of someone scammed like this? How would you handle it?**",

                    // Example 8
                    'Example 8:' . "\n\n" .
                        "\"Creators come in two types:\n" .
                        "- **Machine‑gunners** publish constantly and self‑correct.\n" .
                        "- **Snipers** wait for perfection and aim once.\n\n" .
                        "I side with machine guns for beginners.\n\n" .
                        "In U. of Florida photo class, students graded on quantity vastly improved over those graded on a single ‘perfect’ image.\n\n" .
                        "I cringe at my first 2017 YouTube video—but after 2–3/week, I’m markedly better.\n\n" .
                        "**Advice:** Publish first, refine later—learn by doing, not by overthinking.",

                    // Example 9
                    'Example 9:' . "\n\n" .
                        "2% is our magic inflation target—but nobody knows why.\n\n" .
                        "In 1988, NZ’s Finance Minister off‑handedly set 0–1% as ideal; the central bank settled on 2% for a 1% error margin.\n\n" .
                        "Other countries copied it. 2% wasn’t chosen for science—it stuck because of collective inertia.\n\n" .
                        "**Lesson:** Policy can be arbitrary. Always question ‘why’—even in finance.",

                    // Example 10
                    'Example 10:' . "\n\n" .
                        "A single grain of wheat on square 1 of a chessboard, doubling each square, eventually exceeds **18 quintillion** grains—enough to bankrupt a kingdom.\n\n" .
                        "That’s extreme compounding:\n\n" .
                        "- Start with \$100/mo at 8% annually → \$2.3 M in 45 years.\n\n" .
                        "**Moral:** Small, consistent inputs plus time can yield mind‑boggling results. Start early, and let compounding do its work."
                ],
                'Farnoosh Torabi' => [
                    // Post 1
                    'Example 1:' . "\n\n" .
                        "Ever scared at work? That you may be on the wrong path to \"success\"? That you're being set up for failure?\n\n" .
                        "Thank you, Emily Frieze‑Kemeny, for inviting me to your fantastic podcast, Let's Talk People, to explore the pervasive influence of fear in our careers.\n\n" .
                        "We explored how our fears—ranging from fear of failure to financial anxiety—shape our performance and presence at work. This is a topic that's very personal to me. (see: *A Healthy State of Panic*).\n\n" .
                        "We also dove into the hidden fears that often go unspoken, like the fears surrounding our financial struggles. We discussed strategies for leaders to acknowledge and address these issues while creating a supportive and equitable environment. **Check us out!**",

                    // Post 2
                    'Example 2:' . "\n\n" .
                        "Big (local) update!\n\n" .
                        "I'm excited to share the launch of a new co‑venture called **The Montclair Pod**, a new podcast that blends news, commentary, and voices from around town, anchored by a central topic. Check us out → www.montclairpodcast.com\n\n" .
                        "I've created this with fellow Montclair residents and media pros Alexandra Privitera and Michael Schreiber. Our first two episodes tackle controversial developments in town & the local impact of national policy changes.\n\n" .
                        "Would love for you to check us out and subscribe wherever you listen to podcasts. 🎧",

                    // Post 3
                    'Example 3:' . "\n\n" .
                        "One year ago today, *A Healthy State of Panic* entered the world.\n\n" .
                        "Someone recently asked me, “What kind of magic happened after publishing your book?”\n\n" .
                        "- Making Newsweek’s “Best Books of 2023”?\n" .
                        "- Earning a 4.2 rating on Goodreads (basically an A+ because readers there have no chill)?\n" .
                        "- Launching with Al Roker on the TODAY Show?\n\n" .
                        "But the real magic? I didn’t have an answer… and it made me a little sad. I thought, “Has the book run its course?”\n\n" .
                        "But then I reframed it—what if the book itself is the magic?\n\n" .
                        "It all started in 2019 with a comedy class, an excuse to leave the house.\n\n" .
                        "A 6‑minute set at a NYC club led to a DM from a literary agent. I lied and said I had more material. Months later, I had a proposal and, eventually, a book deal. I had no idea what I was doing—but I crossed the finish line.\n\n" .
                        "The magic isn’t always what happens after, but in realizing: **you did this. When you least expected it.**",

                    'Example 4:' . "\n\n" .
                        "Nobel‑Prize‑winning research finds that those who can resist spending today in favor of saving for tomorrow have better long‑term financial outcomes.\n\n" .
                        "In short, delaying gratification = building wealth 💰\n\n" .
                        "My advice: Make it as easy as possible to get started and stick with it.\n\n" .
                        "✅ Invest automatically\n" .
                        "✅ Start small — even $10 is meaningful\n" .
                        "✅ Create a diversified portfolio using an automated investment platform\n" .
                        "✅ Stick with low‑fee, passive index funds\n\n" .

                        // Example 5…
                        'Example 5:' . "\n\n" .
                        "One year ago today, *A Healthy State of Panic* entered the world.\n\n" .
                        "Someone recently asked me, “What kind of magic happened after publishing your book?”\n\n" .
                        "- Making Newsweek’s “Best Books of 2023”?\n" .
                        "- Earning a 4.2 rating on Goodreads (basically an A+ because readers there have no chill)?\n" .
                        "- Launching with Al Roker on the TODAY Show?\n\n" .
                        "But the real magic? I didn’t have an answer… and it made me a little sad. I thought, “Has the book run its course?”\n\n" .
                        "But then I reframed it—what if the book itself is the magic?\n\n" .
                        "It all started in 2019 with a comedy class, an excuse to leave the house.\n\n" .
                        "A 6‑minute set at a NYC club led to a DM from a literary agent. I lied and said I had more material. Months later, I had a proposal and, eventually, a book deal. I had no idea what I was doing—but I crossed the finish line.\n\n" .
                        "The magic isn’t always what happens after, but in realizing: **you did this. When you least expected it.**",

                    // Example 6…
                    'Example 6:' . "\n\n" .
                        "Ever wish there was a book called *What to Expect In Your Bank Account When Expecting?*\n\n" .
                        "Like, it’s nice to know when my unborn child is sizing like a mango—but how about the cost of labor and delivery? How to stitch together my own paid maternity leave as an entrepreneur? And do I really need to move into a bigger home right away?\n\n" .
                        "The cost of raising a child in America averages over $300k from birth through high school, with those first few years costing tens of thousands in childcare alone.\n\n" .
                        "Excited to announce I’ve been hard at work in partnership with SoFi creating a new financial guide that I wish I’d had before expanding our family.\n\n" .
                        "Download it for free —> www.sofi.com/family\n\n" .
                        "As a financial expert and mom of two, I know firsthand the complexities of raising kids in America. Drawing on research, data, and real‑life stories, I hope my guide will help build a strong financial foundation for your family.\n\n" .
                        "Would love to know what you think!",

                    // Example 7…
                    'Example 7:' . "\n\n" .
                        "My father, who immigrated to the U.S. in his early twenties, has been earning a paycheck for nearly half a century.\n\n" .
                        "Next month, at age 70, he will finally do what we never imagined: retire.\n\n" .
                        "He's already making travel plans and setting sail for Alaska in August.\n\n" .
                        "This moment has gotten me thinking (again) about my retirement plans.\n\n" .
                        "While 70 may not be my ideal retirement age, for Dad, it's been a privilege to work as a scientist (his passion) for as long as he has.\n\n" .
                        "For me, retirement would also mean travel, volunteering, and continuing to smother my children (and possibly grandchildren).\n\n" .
                        "**Did you catch the latest study that says Gen X has saved an average of $150,000 for retirement?** Nearly 50% say they'll need a miracle to retire.\n\n" .
                        "Miracles can happen, but there's also a lot we can do to prepare for long‑term financial security.\n\n" .
                        "To that end, today's So Money podcast answers many of your biggest questions about retirement. Take a listen → https://lnkd.in/eadR4YRf\n\n" .
                        "My co‑host is the amazing Pam Krueger, founder of Wealthramp, and together we cover:\n" .
                        "- How much is enough for a typical retirement?\n" .
                        "- Best ways to save & invest—401(k) vs multiple accounts\n" .
                        "- What to expect from Social Security\n" .
                        "- Preparing for unexpected health expenses\n" .
                        "- Hiring a fiduciary planner\n" .
                        "- Where to store cash after selling your home\n\n" .
                        "**What questions do YOU have about retirement?**",

                    // Example 8…
                    'Example 8:' . "\n\n" .
                        "The #1 strategy that has helped me build a long‑lasting personal brand as a financial educator over the last 20 years is focusing on **MIND SHARE** over **MARKET SHARE**.\n\n" .
                        "An entrepreneur just applied for my mentorship program, and when I asked how she discovered me, she said, “I don’t know. I’ve been following you for years.”\n\n" .
                        "At first, I was a little bummed because I like DATA! But it made me realize that all my efforts in building a sustainable personal brand—are working!\n\n" .
                        "Huge shout out to branding guru LESLIE ZANE, founder of Triggers Brand Consulting, who teaches this in her new book *The Power of Instinct*.\n\n" .
                        "Here’s what it means:\n" .
                        "- Stop chasing likes, follows, downloads\n" .
                        "- Scale in your audience’s mind\n" .
                        "- Show up where trust lives: books, columns, TV, workshops, podcasts, community\n\n" .
                        "**How are YOU building mindshare?**",

                    // Example 9…
                    'Example 9:' . "\n\n" .
                        "Building your career as a speaker can be discouraging a lot of days.\n\n" .
                        "I know there is good money out there for speakers—but women and minorities are often offered a fraction of what men earn.\n\n" .
                        "I've been fortunate to work with organizations that value all speakers, but negotiation was key.\n\n" .
                        "In my latest email, I shared the exact transcript where I nearly 10×d the initial offer.\n" .
                        "Click here to access it: https://lnkd.in/e4rgxz7e\n\n" .
                        "**What’s your #1 tip for sourcing or negotiating speaking gigs?**",

                    // Example 10…
                    'Example 10:' . "\n\n" .
                        "**Serious question for working mothers earning a paycheck:**\n" .
                        "Why do we often say, “Childcare costs more than my income, so I can’t keep working,” when we decide to leave our careers?\n\n" .
                        "Shouldn’t we look at childcare as a percentage of **household** income—unlocking benefits like health care, retirement, & Social Security?\n\n" .
                        "The cost‑benefit math is real—but the narrative often places the burden solely on mothers.\n\n"
                ],
                'Robert Kiyosaki' => [
                    // Example 1
                    'Example 1:' . "\n\n" .
                        '**Success leaves clues!** 🔍' . "\n\n" .
                        'The rich have mentors, advisors, and a community that challenges and supports them. 👩‍🏫 👨‍🏫 🧑‍🏫' . "\n\n" .
                        'We think real education, community, and coaching should be available for anyone interested in taking control of their financial future.' . "\n\n" .
                        'That’s why we created **RichDadPro**—a program designed to give you:' . "\n\n" .
                        '📚 **Real financial education**—proven strategies to start building wealth in real estate without buying property' . "\n\n" .
                        '👨‍🏫 **Live coaching & mentorship**—so you can learn from those who’ve done it' . "\n\n" .
                        '🙌 **A powerful community**—available 24/7 to support you along the way' . "\n\n" .
                        'To celebrate our launch, we\'re offering a limited time **80% discount!** Join us at …',

                    // Example 2
                    'Example 2:' . "\n\n" .
                        '**This is our story.**' . "\n\n" .
                        'Today we share with you our formula to millions... the game of the rich is played... and how we choose to fight academia with capitalism.' . "\n\n" .
                        'My rich dad taught me, “Intelligent people live on the edge, where they can see both sides of the coin.”' . "\n\n" .
                        'F. Scott Fitzgerald agreed saying, “The test of a first‑rate intelligence is the ability to hold two opposed ideas in mind at the same time and still retain the ability to function.”' . "\n\n" .
                        'How can one stand on the edge and hear two opposing ideas if one idea is being actively banished from our consciousness?' . "\n\n" .
                        'Many have warned us not to support Trump or to write my new book coming out soon.' . "\n\n" .
                        '**But we refuse to give in to tyranny. We refuse to give in to the world’s biggest band of bullies.**' . "\n\n" .
                        '**I WANT TO TELL THE TRUTH AS I SEE IT!**' . "\n\n" .
                        'With that, we\'ve asked our team to create this brand story. To simply get our views out, uncensored.' . "\n\n" .
                        'We welcome your feedback.',
                ],
            ],
            'Human Resource' => [
                'Ben Eubanks' => [
                    'Example 1:' . "\n\n" .
                        "HR loves a good policy. 

                            Like it or not, it's a part of life. But policies aren't the answer to everything. 
                            
                            In this fun snippet I created based on the style of the \"If you Give a Mouse a Cookie\" books, I walk through what might be on an HR pro's mind as they think about that policy creation and everything that goes before (and comes after). 😁 😁 😁 
                            
                            If you give HR policy, they'll make a handbook with it. 
                            
                            And making the handbook reminds them that they really need to do a training. 
                            
                            While they're thinking about that training and preparing for it, they'll remember that they have a new onboarding class to teach. 
                            
                            And as they're getting ready for the onboarding class, they'll remember that they need to post a new job requisition. 
                            
                            As they're posting the job requisition, they'll realize that they still need to update some of those job descriptions that have been lingering out there for a while. 
                            
                            And while the job description updates are currently in progress, HR will remember that they needed to look at some of the ranges for compensation to confirm pay equity and market competitiveness. 
                            
                            And as competition is on their mind, they'll wonder about their employer value proposition. 
                            
                            So they'll set a meeting with leadership to talk about their EVP and their culture and their values that they want to use to attract the right candidates. 
                            
                            And while talking to leadership, HR will remember that they have to do an update on HR metrics for the leadership team presentation. 
                            
                            While collecting those metrics, they'll wonder about what's happening with turnover and what's really driving employee retention. 
                            
                            And those thoughts make them think about the importance of managers and how they develop them to be capable leaders who are compliant with legal requirements. 
                            
                            And when they start to think about legal requirements, they'll think about policies and they'll wonder if they need a new one.
                            
                            If this makes you smile, share and pass it on! 😀
                            
                            #hrsummerschool",

                    'Example 2:' . "\n\n" .
                        "I was in a very thoughtful discussion last week with some experts on AI, legislation, and the administration's perspective on technology advancement. 
                            
                            One of the hot news items since January has been the new Trump administration's series of Executive Orders. I'd like to touch on the EO titled: REMOVING BARRIERS TO AMERICAN LEADERSHIP IN ARTIFICIAL INTELLIGENCE. 
                            
                            President Trump is a business and free market guy--always has been, always will be. Therefore it make sense that his approach to AI advancement will rely more heavily on business and industry to make this work. I'm a free market guy, too, but my enthusiasm in this area is tempered by a few important examples. 
                            
                            For starters, the history of deregulation in the US absolutely HAS created more innovation and competition, which are the stated goals of any deregulation. 💯
                            
                            However, we also see unintended consequences. 👎 
                            
                            Some businesses fail. Consumers may end up with worse experiences in some cases (think of the old air travel you see in the movies with full meals and personalized service back a few decades and the cramped experiences most people have today crammed into that narrow metal tube hurtling through the sky). 
                            
                            Deregulation in airlines led to new low cost carriers and more competition, but also to the experience outlined above. 
                            
                            Deregulation in energy led to more control and choice, but also rolling blackouts in California and the Enron-driven market price fixing/gouging. 
                            
                            We're assuming AI companies will be honest, truthful, and forthright. And again, while I believe in and love capitalism, we need some checks and balances, because the AI companies are ALREADY doing shady things & making million$ from it.
                            
                            Take this example. Let's say you're an artist that designs unique cartoons. AI companies are consuming your content to train their models, & anyone can create a cheap AI knockoff of your designs for virtually nothing. Want to try to sue the company to stop them? Good luck. They have virtually unlimited funds because they are actively selling what they have stolen from you. 
                            
                            ⏩ You can't catch up. 
                            
                            This is fundamentally different from a previous time in history where theft was happening at a massive scale. Anyone else remember Napster, Kazaa, and the MP3 download generation? 🎶 
                            
                            In that case, the bigger companies were the ones being wronged since they owned the rights, and they had the money to fight to get their content protected. 
                            
                            So who's protecting the artists, writers, musicians, and other creators? 
                            
                            That's just one example, but it shows that if these companies are already taking the content and ideas of others to train their models, we shouldn't assume they will be honest and fair in their future development, either. 
                            
                            The answer? Well, we need that right balance of government support+advocacy w/enough oversight to make sure these AI companies are creating what will empower and support the population, not weaken it. 
                            
                            Still working out how to balance that one. 🙃  
                            insightfullike  
                            16",

                    'Example 3:' . "\n\n" .
                        "Did you hear the one about the \"AI\" HR Tech company that was shut down by the SEC? 
                            
                            As I'm updating the book on AI, I am changing a lot of names and content. 
                            
                            New innovations? Absolutely. 
                            
                            Updating old references for companies that were acquired or merged with another? Sure. 
                            
                            I didn't expect the need to delete references to companies that were sued and shut down for fraudulent practices. 
                            
                            Joonko started as an AI tool that was supposed to help employers access diverse talent. The AI would capture the \"silver medalists\" that employers didn't hire and put them into a pool so that diversity-conscious employers could tap into that group of candidates as a collective. Ultimately, it would mean more opportunities for diverse candidates to be seen by recruiters, increasing their chances of being hired. 
                            
                            Neat idea, and one that I supported with a mention in the first edition of the book. 
                            
                            Too bad it was pretty much all a sham. 
                            
                            From the Justice Department brief (any RAZ comments are referring to the CEO, Ilit Raz):
                            --------------------------------------------------
                            Joonko was a company that purported to offer an artificial intelligence-based product designed to help prospective employers identify and hire job candidates from diverse backgrounds. ...
                            
                            … (rest of brief) …
                            --------------------------------------------------
                            Wow.
                            
                            Should this scare us away from any company claiming to use AI? Certainly not. It's more of a cautionary tale to investors, but the lesson we can learn from this is pretty clear: 
                            
                            When evaluating #HRtechnology, any vendors that claim \"AI secret sauce\" as a justification simply won't work. 
                            
                            See proof. Try it yourself. Experiment in a sandbox/test environment with some of your own deidentified data.",

                    'Example 4:' . "\n\n" .
                        "In many organizations, HR doesn't struggle to get attention--they struggle to get the RIGHT attention. 
                            
                            Sure, they get looked to for policies and for keeping the business out of hot water. But do they get attention for the right things? 
                            
                            Building a respectful, supportive culture ☺
                            Aligning people to the business needs 📊
                            Attracting and hiring the best talent 🧠
                            Keeping and engaging top performers 🧲
                            
                            I just finished a series with the team at GoCo.io, Inc. and will be sharing some of the videos here in the next week or two. This first one is a great conversation with Nir Leibovich, the CEO at GoCo.io, that talks about what leadership needs from HR. 🤝🏼",

                    'Example 5:' . "\n\n" .
                        "Payroll stories. 📰
                            
                            The inspiring. The challenging. And everything in between. 
                            
                            I'm currently on the tail end of writing my new book The Payroll Promise and would love to include stories within from across the community, either from the perspective of an HR/payroll pro or as an employee. 
                            
                            I'm telling some of my own, such as…  
                            >>> The time a former employer DEBITED my bank account after paying me because they were mad I turned in my notice! #illegal  
                            >>> That time our payroll company screwed up the taxes for one of our employees and they could not submit their state taxes for over a year. 😩  
                            >>> Trying to figure out international payments and realizing that a manager was using cash to pay a Contractor and just putting it on the expense report so that he didn't have to put them on payroll. 😬😬😬  
                            
                            There will be happy stories too! And contributors get a free copy of The Payroll Promise when it launches in March! 🆓🆓🆓  
                            
                            #hrsummerschool",

                    'Example 6:' . "\n\n" .
                        "Did you know that the subtitle of my book was supposed to be something very different? 
                            
                            When I was writing my first book, Artificial Intelligence for HR, way back in 2017 (before the hype cycle!), the publisher dropped in a generic subtitle as a placeholder. 
                            
                            After I began conducting research, I realized I wanted the full title/subtitle to be ***Artificial Intelligence for HR: How to make work more human, not less.*** 
                            
                            So I presented my idea to the publisher... and was told that in spite of the book being more than a year away from publication, the subtitle had already been \"shopped around\" and was no longer able to be changed. 
                            
                            #frustrating 
                            
                            It's always been my position that AI tools should help us to draw our candidates and workforce in closer so we can know and serve them better. 🤗 
                            AI tools should NOT be used simply to keep them at arm's length. ❌  
                            
                            In this upcoming webinar with AMS and Enboarder, we're going to get into the SPECIFICS of how to keep the HUMAN in HIRING. ❤️❤️❤️",

                    'Example 7:' . "\n\n" .
                        "Are your employees worried about their skills in the disruptive age of AI? 
                            
                            Survey says YES!
                            
                            In a Lighthouse Research & Advisory report launching this week in partnership with Adobe Learning Manager, we found that the majority of employees are worried about how AI is going to disrupt the skills they need to do their work. 😫
                            
                            But we also uncovered what high-performing learning cultures do differently to drive engagement, performance, and retention. 🤝
                            
                            Tomorrow, George Rogers and I will be leading a live session at the Adobe Learning Summit in Vegas to share our key findings. 💡💡💡",

                    'Example 8:' . "\n\n" .
                        "When you get asked to speak at a conference or event, it's a special feeling. 🤗
                            
                            When you get asked to come back again and again, it's a REALLY special feeling. 😎
                            
                            That's what I'm thinking this week as I gear up for my session at the ClearCompany #talentsuccessconference #tsc24
                            
                            This year I'll be diving into the relationship between AI and learning—real examples you can put into practice, plus why we must address both AI-in-our-roles and AI-in-the-workplace if we're to be credible business partners. 🤝🏼  
                            
                            It's free to attend. Sign up in the comments below! 👇🏼👇🏼👇🏼",

                    'Example 9:' . "\n\n" .
                        "Have you ever been lost? 🗺
                            
                            And I don't mean lost in the car with GPS, air conditioning, and a cell phone at your fingertips. I mean lost in the woods with none of those amenities. It can be unsettling. 
                            
                            Just like workforce readiness—you can easily get turned around by trends and priorities.  
                            
                            Tomorrow I'll be with Cornerstone OnDemand to talk about workforce agility, skills, and overcoming the readiness gap. 😎  
                            
                            I'll share our latest data on why most employees only learn because they're forced to—and how you can tap into real, voluntary learning hunger.",
                ],

                'Brigette Hyacinth' => [
                    'Example 1:' . "\n\n" .
                        "My NEW EMPLOYEE asked me one day for time off. I immediately approved it. 
            
            She was shocked and asked, “Don’t you want to know the reason why?” My reply was, \"I don't need to know the details. I hired you for a job and I trust you to get it done.\" 
            
            You choose how to get your work done. Come to the office fine. 9 to 5? Fine. Work from home. Fine. Leave early. Fine. Work from the coffee shop? Fine.
            
            We are all human. I don't need to know you will be late because of a doctor's appointment, or you are leaving early to attend a personal matter.
            
            It's sad how we have infantilized the workplace so much, that employees feel the need to apologize for having personal lives. 
            
            Focus on results rather than the number of hours employees spend at their desks.
            
            Agree?",

                    'Example 2:' . "\n\n" .
                        "HR: Posts JOB without SALARY range.
            
            Candidate: Applies
            
            HR: \"We are impressed with your skills and experience. What are your salary expectations?\"
            
            Candidate: \"What is the budget for this role?\"
            
            HR: \"What are your salary expectations?\"
            
            Candidate: \"$10,000\"
            
            HR: \"Sorry but that's outside of our range.\"
            
            Candidate (enraged): Leaves the Interview
            ______
            Please put the salary in the job description. It's a waste of time for companies and candidates when you don't. To be clear… \$62,000 – \$210,000 is not a realistic range. Plus, if it's genuinely a “Competitive Salary,” put a number. Pay transparency promotes equity, efficiency and builds credibility. It's a win-win situation!",

                    'Example 3:' . "\n\n" .
                        "BOSS: I didn't see you log on at 10:00 am.  
            Employee: But we are working from home. I was on a break.
            
            BOSS: That's not the stipulated break times.  
            Employee: But I am getting my work done.
            
            BOSS: I just need to see you online and I need to know your movements.  
            Employee: Okay fine. I am starting and finishing on time. Nothing more.
            
            ____________
            
            Micromanagement is a complete waste of time! It sucks the life out of employees, fosters anxiety and creates a high-stress work environment.
            
            If you want performance at scale:
            
            1️⃣ Select the right people  
            2️⃣ Provide proper training, tools and support  
            3️⃣ Agree on deliverables (be crystal clear)  
            4️⃣ Give them room to get the job done!",

                    'Example 4:' . "\n\n" .
                        "Don’t call your company a Great Place to Work unless you: 👇
            
            1️⃣ Provide appropriate compensation  
            2️⃣ Eliminate toxic work behaviors  
            3️⃣ Treat everyone equally and fairly  
            4️⃣ Appreciate employees  
            5️⃣ Trust employees (Allow flexibility)  
            6️⃣ Invest in employees’ development  
            7️⃣ Provide a psychologically safe environment  
            8️⃣ Practice empathy in the workplace  
            9️⃣ Promote transparency  
            🔟 Hire good managers. \"People don't leave jobs. They leave managers.\"
            
            Companies with happy employees, good benefits and a positive culture—those are signs of a truly great place to work!",

                    'Example 5:' . "\n\n" .
                        "30 INTERVIEWS and still no one HIRED! Yet another friend telling me she cannot find good talent. My thinking is, if you interviewed 30 people and did not hire someone, maybe it's time to start looking in the mirror.
            
            The problem is many recruiters and hiring managers are searching for the perfect candidate. NEWSFLASH! — There is no \"perfect\" candidate.
            
            If you have someone with the right attitude and skill set and they fit in with the team, why not — HIRE them? So many qualified individuals are still job searching. Yet I see the same roles re-posted over and over, vacant for months.
            
            Who took a chance on you? Maybe it’s time you took a chance on someone.
            Agree?",

                    'Example 6:' . "\n\n" .
                        "DISASTER! I interviewed a highly recommended candidate. The interview was a nightmare. She was so nervous she could barely communicate. A deer in the headlights. She BOMBED miserably. Still, I couldn't get past my gut feeling she was the best candidate for the job.
            
            Is it possible to overlook a poor interview performance?
            
            I gambled—and within 6 months she was one of my top performers. Sometimes it’s hard to know a candidate's full capabilities in a job interview.
            
            We shouldn't be too quick to cross someone off who doesn’t interview well. Interviews can be nerve-wracking—there is so much more to a person than just passing/failing an interview.
            
            Agree?",

                    'Example 7:' . "\n\n" .
                        "This was the day after I resigned from my job. A lot has changed since this photo, but I remember how excited and scared I felt for the next chapter.
            
            Someone asked me to describe the plunge from corporate to entrepreneurship. I said it’s like leaving a pool to swim in the ocean.
            
            My advice: Plan and prepare carefully. I built my business part-time so I was already testing the waters. I set aside emergency cash and cleared major debts.
            
            Entrepreneurship is not a walk in the park nor for the faint of heart—I’ve faced many ups and downs, and at times the downs seem greater than the ups. But there is no greater feeling than investing in your dreams each day.
            
            If you dread the clock at your job, start taking small steps toward your own. Purpose. Passion. Persistence. Faith. “With God all things are possible.” If you put in the work, the results will come.",

                    'Example 8:' . "\n\n" .
                        "I got REJECTED for an internal position I applied for—even though I was the top candidate. I later learned the person hired was a friend of my boss.
            
            I got tired of the politics and applied elsewhere—and within 3 months landed a much better role.
            
            My old boss asked me to come back for the same promotion. I said “Thanks but no thanks.”
            
            Lessons Learned:
            
            1️⃣ You can be the best candidate and still not get selected.  
            2️⃣ If you didn't get the job, it was never meant for you.  
            3️⃣ You weren't rejected, you were redirected.  
            4️⃣ Your value does not decrease based on someone's inability to see your worth.  
            5️⃣ Believe in yourself and keep trying—the right door will open for you.",

                    'Example 9:' . "\n\n" .
                        "Company Culture is not about free lunches, swag or office parties.
            
            Company Culture is about:
            
            1. Creating a safe space for employees  
            2. Open, honest communication  
            3. Welcoming feedback  
            4. Great work-life balance  
            5. Flexibility and autonomy  
            6. Empathy, respect and kindness  
            7. Appreciation and recognition  
            8. Ongoing growth opportunities  
            9. Zero tolerance for politics/toxic behaviors  
            10. Supportive leadership
            
            Ultimately, culture is defined by how people are treated day in, day out.",

                    'Example 10:' . "\n\n" .
                        "I saw a LinkedIn post from someone recently laid off—0 likes. I liked, commented, and tagged a couple recruiters. On Friday he emailed me that he’d accepted an offer and was grateful for the boost.
            
            Job searching is emotionally draining. When no one engages, it feels like extra rejection. So when you see such posts, take a moment to like, comment or share. You may not hire them yourself, but you’ll help get them seen.
            
            That’s what true networking is about. Let’s help each other out.
            Agree?",
                ],

                'David Green' => [
                    'Example 1:' . "\n\n" .
                        "How S&P Has Embedded Workforce Planning Into Business Strategy\n\n" .
                        "“What I really try to make sure we're doing from a workforce planning lens, and this echoes Dave Ulrich, is to start from the outside in. The business is constructed to deliver value to their customers, and financial success is a key driver of that. Most of the return on investment of any work in workforce planning is going to have some sort of financial or cost impact.”\n\n" .
                        "In this week’s episode of the Digital HR Leaders podcast my guest — Alan Susi, Vice-President and Global Head of Organisational Analytics and People Insights at S&P Global — and I explore exactly what it takes to transform workforce planning into a true strategic capability. One that's built on data, strong relationships – particularly with finance, and a clear connection to business outcomes.\n\n" .
                        "🗣️ Alan on partnering successfully with finance:\n“We've done it in the way that we tried to really get in the weeds with Finance on the connectivity of data. We focus on understanding, ‘How could our people data solve some of your challenges?’ Whether it's bringing much more depth to providing attrition in their planning. Or whether it is the labour market data that informs making a better location strategy decision.”\n\n" .
                        "In our conversation, Alan and I discuss:\n" .
                        "🔎 How S&P Global is embedding workforce planning into core business decision-making\n" .
                        "🔎 Why Finance is such a key partner in making workforce planning successful\n" .
                        "🔎 The role of AI and technology in shaping the future of workforce planning and analytics\n" .
                        "🔎 What leading companies are doing differently, and what others can learn\n" .
                        "🔎 Why Organisational Effectiveness, Workforce Planning and People Analytics are inextricably linked.\n\n" .
                        "🗣️ On the need for more ‘nimble’ workforce planning:\n“There’s no three-year or five-year workforce planning approach. Are you agile or nimble enough to understand the business challenge? ‘Can I give you some views so that when we see things change, you can react quicker?’ That to me is how workforce planning needs to evolve. I've termed it as kind of minimum viable workforce planning.”\n\n" .
                        "🙏 Thanks to Alan for sharing his time and expertise with listeners – especially as the episode was recorded on his birthday 🎂. Thanks too to Philip Arkcoll, Laura Morris and the Worklytics team for sponsoring this series of the podcast, as well as Stefan Kesić, Jasmine Panayides, and the teams at Insight222, myHRfuture and Listen for bringing the podcast to life.\n\n" .
                        "#workforceplanning #peopleanalytics #orgdesign #humanresources #futureofwork #hrpodcast",

                    'Example 2:' . "\n\n" .
                        "How is AI reshaping the HR operating model?\n\n" .
                        "This was the research question that Volker Jacobs and his team at TI People sought to answer through a co-creation study developed with 15 major European companies (including Novartis and BASF) who provided more than 2,500 data points to validate and train multiple large language models.\n\n" .
                        "🔎 29% average efficiency potential across HR functions\n" .
                        "📈 €5.2M in annual cost savings potential that can be reinvested\n" .
                        "✂️ HR Specialist Operations roles face 50% efficiency impact, and;\n" .
                        "💰 HR Business Partners see 19% efficiency potential while being able to deliver more strategic value.\n\n" .
                        "Volker then presents a new HR Operating Model (see Figure), which is composed of four components:\n\n" .
                        "1️⃣ Product Managers owning end-to-end HR products aligned to key user needs.\n" .
                        "2️⃣ Problem Solvers working across traditional boundaries to address complex business challenges.\n" .
                        "3️⃣ Service Delivery teams orchestrating AI-human collaboration.\n" .
                        "4️⃣ AI handles increasing portions of transactional and analytical work.\n\n" .
                        "The research is innovative in that its findings are compiled in an AI-tool. This tool is ready for companies to use and obtain detailed, comprehensive and validated insights into the AI-future of their HR function within 48 hours.\n\n" .
                        "#humanresources #chiefpeopleofficer #hroperatingmodel #peopleanalytics #employeeexperience #hrtransformation",

                    'Example 3:' . "\n\n" .
                        "How Remote Work Changes Design Thinking\n\n" .
                        "“A key advantage of the design-thinking process over other innovation methods is its emphasis on the user experience.”\n\n" .
                        "Design thinking and user-centred design are critical tools in building an exceptional employee experience – and HR practitioners can learn much from how these tools are applied to customer experience.\n\n" .
                        "As outlined in the Figure, effectively combining physical and virtual formats throughout the design-thinking process allows innovation leaders to harness the distinct advantages of each setting.\n\n" .
                        "#employeeexperience #designthinking #remotework #culture #peopleanalytics",

                    'Example 4:' . "\n\n" .
                        "What is the role of People Analytics and ONA in measuring AI adoption and business impact?\n\n" .
                        "“In teams where the manager is a high active user of AI, we've seen an uplift of up to 5X in how much their direct team uses AI as well.”\n\n" .
                        "In this week’s episode of the Digital HR Leaders podcast, I am joined by Erik Schultink, CTO and Co-Founder of Worklytics, to explore how organisations can move beyond the hype and start measuring the real impact of AI in the workplace.\n\n" .
                        "“Organisational Network Analysis is a critical tool for understanding the impact of AI. (With ONA) we’ve already seen that new hires are much bigger adopters of AI than the people who have been in the company a long time.”\n\n" .
                        "In our conversation, Erik and I discuss:\n" .
                        "💡 Why most companies aren't seeing the tangible value of their AI investment\n" .
                        "🔎 What leading organisations are doing differently to embed AI into the flow of work\n" .
                        "📈 How people analytics teams can track meaningful signals of successful AI adoption\n" .
                        "📊 The role of Organisational Network Analysis in improving AI integration\n" .
                        "🪜 Practical steps for HR leaders looking to shape a responsible, people-first AI strategy\n\n" .
                        "“Executives are asking People Analytics: Where do we sit on AI adoption? How are we rolling it out? What do the skills of our workforce look like in utilising these tools? Do we have the right skills? Are people then using this? Where are our areas of opportunities? Where are our maximum points of leverage?”\n\n" .
                        "Thanks to Erik for sharing his time and expertise with listeners as well as Philip Arkcoll, Laura Morris and the Worklytics team for sponsoring this series of the podcast. Thanks too to Stefan Kesić, Jasmine Panayides, and the teams at Insight222, myHRfuture and Listen for bringing the podcast to life.\n\n" .
                        "#peopleanalytics #organizationalnetworkanalysis #chiefpeopleofficer #humanresources #futureofwork #hrpodcast #employeeexperience",

                    'Example 5:' . "\n\n" .
                        "How do you measure the impact of AI adoption in your organisation?\n\n" .
                        "1️⃣ Adoption (focused on uptake)\n❓ Key Question: “How many people are using AI each day / week / month?”\n\n" .
                        "2️⃣ Proficiency (focused on impact)\n❓ “What percent of work being done is assisted by AI?”\n\n" .
                        "3️⃣ Leverage (focused on productivity gains)\n❓ “Are we getting more done in the day with AI than we were without it?”\n\n" .
                        "“Executives want to know where their org falls on the spectrum – and they’re asking People Analytics to give them the numbers.”\n\n" .
                        "#humanresources #peopleanalytics #organizationalnetworkanalysis",

                    'Example 6:' . "\n\n" .
                        "“Skills-based talent management cannot scale or be sustainable long-term without AI-enabled skills management.”\n\n" .
                        "Writing for Gartner, Helen Poitevin presents an AI use-case prism for human capital management (see Figure) before providing detailed insights on AI in HR across three key areas:\n\n" .
                        "1️⃣ AI in Recruiting\n🔎 “The next frontier for recruiting is the AI avatar recruiter. This AI agent would take on the role of the recruiter and be the primary point of contact for hiring managers throughout the recruiting process...we predict that by 2028, 10% of hiring managers will work with an AI avatar recruiter to fill roles, up from less than 1% today.”\n\n" .
                        "2️⃣ HR Virtual Assistants\n🔎 “The vast majority of current HR application user interfaces are based on a visual interface, with a very limited conversational interface. In the next decade, we can expect to see these interfaces change, with a significant majority transitioning to conversational.”\n\n" .
                        "3️⃣ AI-Enabled Skills Management\n🔎 “Gartner predicts that by 2028, 40% of large organizations will have invested in two or more AI-enabled skills management solutions with the aim of delivering business agility. In the first stage of roll-out, organizations will need to rely on multiple solutions. This will make data messier, so HR teams should share data across solutions wherever possible.”\n\n" .
                        "Thanks to Brian Heger for highlighting in his excellent Talent Edge Weekly newsletter.\n\n" .
                        "#humanresources #recruiting #hrtech #futureofwork #workforceplanning #peopleanalytics #skillsbasedorganization #chiefpeopleofficer",

                    'Example 7:' . "\n\n" .
                        "How do People Analytics teams differ by region?\n\n" .
                        "In a recent edition of Crunchr’s newsletter, The HR Crunch, Dirk Jonker and Ralf Bovers provide some illuminating insights into the size and location of companies that have people analytics teams (see Figure) with the US and larger companies leading the way.\n\n" .
                        "Key findings of their analysis include:\n" .
                        "📊 U.S. companies lead the way in having dedicated people analytics professionals or teams.\n" .
                        "📊 Across all regions, larger companies are more likely to have at least one people analytics professional.\n" .
                        "📊 There is still a significant opportunity for companies of all sizes to establish a people analytics function.\n\n" .
                        "#peopleanalytics #humanresources #chiefpeopleofficer #hrtech",

                    'Example 8:' . "\n\n" .
                        "Seven Golden Rules For Improving Employee Experience\n\n" .
                        "They share seven ‘Golden Rules’ for improving employee experience including:\n" .
                        "🔎 Applying the ‘Triple Diamond Model’ in order to capture, understand and act on your employees' needs and ambitions (see Figure)\n" .
                        "🗣️ “Effective EX solutions are built on an iterative, evidence-based approach: co-creating with employees through workshops and prototyping, testing ideas early with A/B testing, and using qualitative insights for ongoing improvement.”\n\n" .
                        "🔎 Ensuring strategic positioning\n🗣️ “EX is a strategic capability, so the responsible team should be positioned in HR accordingly.”\n\n" .
                        "🔎 Aiming for hyper-personalisation\n🗣️ “By understanding employee differences, organisations can tailor experiences to be more personalised and meaningful.”\n\n" .
                        "#employeeexperience #humanresources #peopleanalytics #culture #employeelistening",

                    'Example 9:' . "\n\n" .
                        "How do you measure the impact of AI adoption in your organisation?\n\n" .
                        "1️⃣ Adoption (focused on uptake)\n❓ Key Question: “How many people are using AI each day / week / month?”\n\n" .
                        "2️⃣ Proficiency (focused on impact)\n❓ “What percent of work being done is assisted by AI?”\n\n" .
                        "3️⃣ Leverage (focused on productivity gains)\n❓ “Are we getting more done in the day with AI than we were without it?”\n\n" .
                        "“Executives want to know where their org falls on the spectrum – and they’re asking People Analytics to give them the numbers.”\n\n" .
                        "#humanresources #peopleanalytics #organizationalnetworkanalysis",

                    'Example 10:' . "\n\n" .
                        "“The CHRO role is critical for business success, with CHROs serving as C-suite leaders first, and HR function leaders second.”\n\n" .
                        "What are the secrets of high-performing chief people officers?\n\n" .
                        "Josh and Kathi's study outlines the role of the CHRO, career trajectories, education, experiences, and high-level success drivers, along with the implications for leaders.\n\n" .
                        "Findings include:\n\n" .
                        "🔎 More than 75% of CHRO appointments come from the outside, indicating a lack of CEO confidence in HR and/or a lack of succession planning for this job.\n\n" .
                        "🔎 There are four major archetypes of CHRO (see Figure):\n" .
                        "👉 Career CHRO (who change companies regularly)\n" .
                        "👉 Company CHRO (who grow up inside the company)\n" .
                        "👉 Business CHRO (who are rotated into the job from non-HR roles)\n" .
                        "👉 Operations CHRO (who come from legal, finance, or operations backgrounds)\n\n" .
                        "🔎 Business CHROs drive the greatest change and impact.\n\n" .
                        "🔎 While 69% of CHROs are female, those aiming to advance to the CHRO role often need to switch companies frequently, rather than work their way up in one organisation.\n\n" .
                        "#chiefpeopleofficer #humanresources",
                ],
                'Hung Lee' => [
                    'Example 1:' . "\n\n" .
                        "THIS WEEK IN RECRUITING\n\n" .
                        "BEST WAYS TO RESPOND TO FAKE JOB CANDIDATES....\n" .
                        "Did you want Brainfood Live last Friday? Important discussion on the challenges recruiters face in the era where AI has diffused across the world, leading to increased risk of fraudulent job applications.\n\n" .
                        "5 ideas on how recruiters might respond....\n\n" .
                        "1️⃣ SWITCH FROM JOB ADVERTISING TO SOURCING\n" .
                        "Most fraud cases come from applicants toward public job adverts, so why not switch to non-public methods of talent attraction? Reports of the death of sourcing may be premature!\n\n" .
                        "2️⃣ INCREASE ASSESSMENT (BUT MAKE IT HARDER TO GAME)\n" .
                        "High volume hirers are going to have to invest in techniques to increase friction in between job advert and recruiter inbox. CAPTCHA, Pre-screening questions, Asynch video bio, AI interviewer...something / anything?\n\n" .
                        "3️⃣ PUSH ID VERIFICATION/REFERENCE CHECKING FURTHER UP THE HIRING FUNNEL\n" .
                        "Blind references – traditionally considered taboo in TA/HR – will move from the margins to the mainstream. Where do we think the responsibility is going to lie if fraudulent candidates make it through to the hiring manager?\n\n" .
                        "4️⃣ OFFLOAD WORKLOAD TO THIRD PARTY RECRUITERS\n" .
                        "I can see a revival of the staffing/RPO market IF they can provide the ID verification/candidate assurance which currently internal teams do not have the time for. Job boards and talent-matching marketplaces might also get in on the action here.\n\n" .
                        "5️⃣ ROLL BACK REMOTE\n" .
                        "Simplest thing will be the most likely thing. Unfortunately the shift to remote increases the risk surface area for fraud and absent effective techniques 1-4 above, a lot of companies are going to do the simplest thing and RTO.\n\n" .
                        "What other techniques can you think of that recruiters and employers might employ to mitigate against fraudulent job candidates? Let me know in comments, be good to start a conversation on this!\n\n" .
                        "Thanks to our friends Ashby for supporting this week's newsletter. Ashby One on 15 May – make sure you register and be amongst the first to be briefed on product keynote for 2025/6 – \n\n" .
                        "-------------------------------------------------------------------------------\n\n" .
                        "WHAT IS GOING ON\n" .
                        "- Big List of Recruiting / HR Events in 2025\n" .
                        "- 4 Ways Talent Acquisition will use AI w/ Kristjan Kristjansson of 50skills\n" .
                        "- AI Regulations for TA / HR w/ Martyn Redstone, Bob Pulver Nina Alag Suri and friends\n" .
                        "- Tech Talent Summit w/ HackerRank\n" .
                        "- How to use Leverage Passive Candidates on Social Media w/ Doug Monro of Adzuna\n" .
                        "- Tech Talent Day Demo w/ Strivin\n" .
                        "- Turning Inbound Overload into Qualitative Advantage w/ Covey\n" .
                        "- Worksummit 2025 w/ Worksome\n" .
                        "- TA Tech June 3 in San Diego",

                    'Example 2:' . "\n\n" .
                        "4 x ways Talent Acquisition will use AI\n\n" .
                        "Want to speed up hiring and cut admin without switching tools?\n\n" .
                        "AI workflows make this possible. In this session, we’ll explore four ways TA teams are using AI to improve their hiring journey.\n\n" .
                        "Hung Lee, Editor of Recruiting Brainfood, joins Kristjan from 50skills for a candid conversation about what’s working right now. You’ll also hear from 50skills customers who are using AI in real workflows to solve real challenges.\n\n" .
                        "This isn’t a product demo. It’s a grounded, real-world look at how AI and automation are being applied across the talent journey, shared by the people who are actually doing it.\n\n" .
                        "What we’ll cover\n" .
                        "• Four ways TA teams are using AI right now\n" .
                        "• How to get more out of the tools you already use\n" .
                        "• Real examples from 50skills customers\n" .
                        "• What makes a workflow builder a smart addition to your ATS",

                    'Example 3:' . "\n\n" .
                        "THIS WEEK IN RECRUITING\n\n" .
                        "BEST WAYS TO RESPOND TO FAKE JOB CANDIDATES....\n" .
                        "Did you want Brainfood Live last Friday? Important discussion on the challenges recruiters face in the era where AI has diffused across the world, leading to increased risk of fraudulent job applications.\n\n" .
                        "5 ideas on how recruiters might respond....\n\n" .
                        "1️⃣ SWITCH FROM JOB ADVERTISING TO SOURCING\n" .
                        "… [same content as Example 1] …",

                    'Example 4:' . "\n\n" .
                        "THIS WEEK IN RECRUITING – ISSUE 213\n\n" .
                        "EVERYONE NEEDS A PLAN B FOR YOUR 40'S…\n" .
                        "… [rest of Post 4 content] …",

                    'Example 5:' . "\n\n" .
                        "WHAT’S HAPPENING NEXT 4 WEEKS\n\n" .
                        "Friends, we have a stacked schedule for the next 4 weeks!\n\n" .
                        "Which one of these are you registering for?\n\n" .
                        "1️⃣ AGENTIC IN THE WILD – LIVE DEMO\n" .
                        "…\n\n" .
                        "2️⃣ MITIGATION STRATEGIES VS FAKE JOB CANDIDATES\n" .
                        "…\n\n" .
                        "3️⃣ 4 x WAYS TA WILL USE AI\n" .
                        "…\n\n" .
                        "4️⃣ AI REGULATIONS IN TA & HR – 2025 Q2 UPDATE!\n" .
                        "…\n\n" .
                        "5️⃣ TRANSITIONING TO CV FREE HIRING\n" .
                        "…\n\n" .
                        "6️⃣ RECRUITING RE-IMAGINED IN THE AI ERA\n" .
                        "…\n\n" .
                        "7️⃣ HOW TO BUILD CAREER PAGES WHICH ACTUALLY WORK\n" .
                        "…\n\n" .
                        "8️⃣ HIRING MANAGER SELF SERVICE – THE PAST…AND FUTURE OF RECRUITMENT?\n" .
                        "…",

                    'Example 6:' . "\n\n" .
                        "High Volume Hiring – How Recruiters Can Manage the Tsunami of Applications\n\n" .
                        "Hung Lee (Recruiting Brainfood) and Doug Monro (Adzuna CEO and Co-Founder) host the first in a four-part webinar series on the issues Talent Acquisition teams are facing right now in High Volume Hiring.\n\n" .
                        "… [rest of Post 6 content] …",

                    'Example 7:' . "\n\n" .
                        "THIS WEEK IN RECRUITING – ISSUE 212\n\n" .
                        "THE COMING BIFURCATION OF HIRING\n\n" .
                        "1. CAN AI DO IT?\n" .
                        "…\n\n" .
                        "2. DO WE KNOW AI CANNOT DO?\n" .
                        "…\n\n" .
                        "3. FROM PAYROLL PYRAMIDS TO CONSTELLATIONS OF TALENT\n" .
                        "…\n\n" .
                        "4. TA ROLE IN THIS UNIVERSE\n" .
                        "…\n\n" .
                        "5. RECOMMENDATIONS\n" .
                        "…",

                    'Example 8:' . "\n\n" .
                        "AI + TA/HR + Regulations......\n\n" .
                        "Friends, who is the person who knows most about how we can/should/will use AI in recruiting and HR?\n\n" .
                        "… [rest of Post 8 content] …",

                    'Example 9:' . "\n\n" .
                        "RECRUITERS, HOW ARE YOU DEALING WITH HIGH VOLUME JOB APPLICANTS?\n\n" .
                        "1️⃣ 'Peak-a-boo' job ads\n" .
                        "2️⃣ Programmatic job ads\n" .
                        "…\n\n" .
                        "8️⃣ ?\n\n" .
                        "EDIT: me and Doug Monro are doing a webinar on this – come join https://lnkd.in/ewSGBhuG",

                    'Example 10:' . "\n\n" .
                        "WHY ARE WE 'DOING AI'?\n\n" .
                        "Rhetorical question because I know your answer: to increase efficiency by eliminating repetitive manual tasks.\n\n",
                ],
                'Jan Tegze' => [
                    'Example 1:' . "\n\n" .
                        "If you’ve made it to the interview stage, you’re already doing something right. Don’t forget that.\n\n" .
                        "It means someone saw potential in you.\n\n" .
                        "They looked at your résumé and said, “Let’s talk to this person.”\n\n" .
                        "That’s not luck. \n" .
                        "That’s effort. \n" .
                        "That’s your experience, your growth, and your skills shining through.\n\n" .
                        "Interviews can be scary. But just showing up is a win.\n\n" .
                        "So take a deep breath. \n" .
                        "Be proud. \n" .
                        "And keep going.\n\n" .
                        "likelovesupport\n" .
                        "248",

                    'Example 2:' . "\n\n" .
                        "Are you looking for a new job? Let me help you! \n" .
                        "Join my webinar, “How to Use AI in Your Job Search.”\n\n" .
                        "In one hour, you’ll learn easy tips to:\n\n" .
                        "🟢 Improve your résumé with AI tools\n" .
                        "🟢 Learn how to check if your resume is ATS-friendly\n" .
                        "🟢 Practice interview questions with chatbots\n" .
                        "🟢 Insider tactics to stand out in today’s data-driven hiring landscape\n\n" .
                        "Date & Time\n" .
                        "Sunday, May 4, 2025 – 3:30 PM CEST\n\n" .
                        "👉 Save your spot in under 30 seconds — click here to register.\n\n" .
                        "Can’t make it live? No worries! Everyone who signs up will get the full replay plus all the AI prompts we cover.",

                    'Example 3:' . "\n\n" .
                        "👋 For the next 60 minutes, I’m trying something new! If you’re job hunting, need feedback on your LinkedIn profile, or have questions about your job search, join me for this Q&A Job Search Guide session. Think of it as an interactive Zoom meeting (Hopefully, at least one person shows up.!). 😁\n\n" .
                        "When you join, simply raise your hand, and I’ll unmute you so we can discuss your job search questions, ways to improve your LinkedIn, and other things.\n\n" .
                        "Meeting link: Thank you all for joining—it was such a great time! An hour turned into two. 🫣\n\n" .
                        "Perhaps it’s a crazy idea destined to fail miserably, but let’s see how this goes! 😊",

                    'Example 4:' . "\n\n" .
                        "Remember when Excel became a must-have skill for so many jobs?\n" .
                        "We’re in that moment again — but this time, it’s AI.\n\n" .
                        "Understanding AI is like learning Excel in the 2000s. You don’t want to be the only one who can’t speak the new language.\n\n" .
                        "You don’t need to become a tech wizard. But you do need to know how tools like AI are helping people write faster, solve problems, and do more with less.\n\n" .
                        "The workplace is changing fast. The people who learn with it will have more options, more freedom, and more impact.\n\n" .
                        "Don’t wait for someone to make you learn it.\n" .
                        "Start learning it now, and you’ll thank yourself later.",

                    'Example 5:' . "\n\n" .
                        "Not every job interview will go well. That doesn’t mean you failed. It means you're human.\n\n" .
                        "You might get nervous. \n" .
                        "You might forget a word. \n" .
                        "You might not connect with the person on the other side.\n\n" .
                        "And that’s okay.\n\n" .
                        "Each interview, regardless of the outcome, is a chance to learn and grow.\n\n" .
                        "One rough interview doesn’t erase your value.\n\n" .
                        "The right opportunity, where your unique strengths are recognized and celebrated, is out there.\n\n" .
                        "Reflect. Adapt. Keep showing up.",

                    'Example 6:' . "\n\n" .
                        "Have you ever used AI to prepare for a salary negotiation? 🤖\n" .
                        "Here’s the thing: AI isn’t just for coding or content.\n\n" .
                        "You can use it to draft professional, respectful negotiation messages that reflect your tone and your goals.\n\n" .
                        "From polishing your email to practicing what to say on a call, AI can help you feel prepared and confident—without sounding like a robot.\n\n" .
                        "If you’re curious how to use AI to navigate job conversations like this, I’ve got a simple prompt that makes it easy.\n\n" .
                        "Basic Prompt:\n" .
                        "----------------\n\n" .
                        "Help me write an email to negotiate a job offer.\n" .
                        "To: [Hiring Manager’s Name] ([Their Job Title] at [Company Name])\n" .
                        "From: [Your Name]\n" .
                        "Context: I received a job offer for [Job Title] at [Company Name].\n" .
                        "Goal: Negotiate for a higher salary or improved offer.\n" .
                        "Request: Ask respectfully for a salary adjustment based on my research, skills, or market standards.\n" .
                        "Tone: Polite, professional, and appreciative of the offer.\n\n" .
                        "-----------------\n\n" .
                        "The most valuable skill in 2025? Knowing how to work with AI, not against it.\n" .
                        "Learn how to talk to AI!",

                    'Example 7:' . "\n\n" .
                        "Applying for jobs in 2025:\n" .
                        " • Upload résumé\n" .
                        " • Re-type résumé\n" .
                        " • Write a cover letter\n" .
                        " • Answer 30 questions\n" .
                        " • Record a video\n" .
                        " • Get ghosted\n\n" .
                        "It’s exhausting. And it’s not just you — a lot of amazing people are feeling it.\n\n" .
                        "If you're tired, that’s normal. If you're frustrated, that makes sense.\n" .
                        "But please remember: You're more than this process.\n\n" .
                        "You're not just a link in a hiring pipeline. You’re a real person with real skills and real value.\n\n" .
                        "Don’t give up. Rest if you need to. But don’t give up.\n\n" .
                        "The right opportunity is still out there — and it will be lucky to have you.",

                    'Example 8:' . "\n\n" .
                        "Tired of your LinkedIn connection requests vanishing into the void? 😱\n" .
                        "Want to increase your acceptance rate? Send a custom message. It shows genuine interest and respect for their time.\n\n" .
                        "Use this simple prompt framework to create a personalized request in under a few seconds: 👇\n\n" .
                        "Prompt:\n" .
                        "-----------------\n\n" .
                        "Help me write a short LinkedIn connection request message.\n" .
                        "I want to connect with [Person's Name], who works as a [Person's Job Title] at [Company Name].\n" .
                        "My reason for connecting is [Your reason, e.g., I'm interested in their work in X field, I admire their company, We share a connection/group].\n" .
                        "Keep the message under 300 characters.\n\n" .
                        "-----------------\n\n" .
                        "It's a small step that makes a HUGE difference. You can try the advanced prompt included in the attached PDF.\n\n" .
                        "Networking smarter, not just harder!\n\n" .
                        "Find this prompt helpful?",
                ],
                'Suzanne Lucas' => [
                    'Example 1:' . "\n\n" .
                        "I love a good spreadsheet as much as the next person. Pivot tables are super fun. I haven't done a vlookup in years, though.\n\n" .
                        "Excel can be awesome, but it's not for everything. If you're not careful, putting in the wrong formula can result in a \$6 billion loss.\n\n" .
                        "Read why you might want to consider specialized software for specialized tasks, like compensation work here: https://lnkd.in/ehC5wgyT",

                    'Example 2:' . "\n\n" .
                        "Are you looking for a new job in HR?\n\n" .
                        "If you are, you probably feel stuck and discouraged. It's hard out there!\n\n" .
                        "To help, I've put together a FREE checklist to help you keep your brain focused when you're looking for a job.\n\n" .
                        "Yes, as an HR professional you know more about hiring than anyone else, but somehow it makes your own brain hurt when you're doing the job search yourself. This takes the chaos out of it and puts your job hunt into a nice checklist.\n\n" .
                        "Find your FREE checklist here: https://lnkd.in/etq57m5G",

                    'Example 3:' . "\n\n" .
                        "Have you been in a video meeting where one person has their camera on and the other has it off?\n\n" .
                        "If you join a meeting with your camera on and the other person/people have it off, do you turn your camera off, or do you leave it on?\n\n" .
                        "Do you turn yours on if you join a meeting with your camera off and the others have theirs on?\n\n" .
                        "Does it matter what the meeting is for? For instance, if you're a job candidate, and the hiring manager/recruiter has their camera off, do you leave your camera on, but if you're meeting with a sales person, and they have their camera on, do you feel comfortable turning yours off?\n\n" .
                        "What's the deal?\n\n" .
                        "I feel like this should be standard, these days. And my personal opinion is, cameras should be the default for meetings with new people.\n\n" .
                        "likecelebratesupport\n" .
                        "36",

                    'Example 4:' . "\n\n" .
                        "If you post a salary range for a job, is having a candidate say they are looking for the middle of that range acceptable?\n\n" .
                        "My friend, \"Tiffany\" (I've changed her name to protect her privacy), thought so. So, when the job posting listed a salary of \$104,000 to \$194,000, she figured a nice midpoint of \$155,000 to \$165,000 was completely reasonable. (It was also a reasonable salary given the market rate and her skill set.)\n\n" .
                        "So why did the recruiter respond, “The range for this job is \$104,000 to \$120,000. That’s way outside the range.”\n\n" .
                        "It turns out that this particular company just posts jobs with wild ranges.\n\n" .
                        "This is why people hate recruiters. This is why people hate HR (because they think of recruiters when they think of HR.) This is why we all deserve to be hated.",

                    'Example 5:' . "\n\n" .
                        "Two \"sales people\" offering services that I actually do need (creating a new speaker reel and getting more webinar clients) and would be willing to pay for, sent messages. I appreciate the hard work it takes to get new clients, so I responded to both.\n\n" .
                        "It became rapidly clear neither sales pitch was from a human. Some of the clues:\n\n" .
                        "🤖 Messages that gave instructions on how to respond (give a thumbs up if you want to know more)\n" .
                        "🤖 Rapid response with a multi-paragraph \"answer\" to my question that used my key words, but didn't answer the actual question.\n" .
                        "🤖 Stilted word choice that reads like AI\n" .
                        "🤖 Switching to French while telling me it would communicate in English. (Lots of people pitch me in German and French because of my location. I do speak German and indicate so on my LinkedIn profile, but my French is limited to about three phrases)\n\n" .
                        "I won't do business with these companies. If they are too understaffed to have a human respond to me, I can't trust that they can create a product I can use. I can use AI myself to create a speaker reel. ChatGPT will give me great ideas on how to get more webinar clients. Why would I pay someone to put something into a large-language-model and spit out an answer?\n\n" .
                        "Have you found success using AI for sales pitches? I'd love to hear about it.\n\n" .
                        "Would you work with a company that used a bot to try to sell to you?",

                    'Example 6:' . "\n\n" .
                        "HR keeps companies alive. But who's keeping us alive?\n\n" .
                        "And if you’re thinking, ‘It’s not just me, right?’ Nope. It’s ALL of us.\n" .
                        "Let’s be real—this job is brutal.\n\n" .
                        "📊 81% of HR pros are completely burned out.\n" .
                        "📊 50% are actively thinking about quitting.\n" .
                        "📊 62% of HR leaders are considering leaving HR entirely.\n" .
                        "📊 95% say the workload is simply too much.\n" .
                        "📊 Nearly 100% have felt burned out in the last six months.\n" .
                        "📊 84% report experiencing regular high stress on the job.\n\n" .
                        "If you’re in HR, you don’t need stats to tell you what you already know—you feel it. The weight of this job is crushing, and the worst part? Almost no one is looking out for us. Not leadership, employees, or even the HR industry and association groups that should have our backs. We’re out here drowning, and the world just assumes we’ll keep treading water.\n\n" .
                        "We can’t fix everyone else if we’re breaking ourselves in the process.\n" .
                        "If you’re ready to take action, don’t miss this: HRLearns is hosting a must-attend webinar that speaks directly to this crisis.\n\n" .
                        "🎙️ \"When There Is No HR for HR: How to Prevent Burnout and Improve Your Well-Being.\"\n" .
                        "📅 Date: Tuesday, March 18\n" .
                        "💻 Format: Virtual Event\n" .
                        "Presenter: Neelie Verlinden\n" .
                        "Co-hosts: Victoria Purser, SPHR, SHRM-SCP, CEO of ConquerHR® and Co-founder of HRLearns™ and me, Founder and CEO of Improve Your HR, Co-founder of HRLearns™\n\n",
                ],
            ],
            'Marketing' => [
                'Alex Liberman' => [
                    'Example 1:' . "\n\n" .
                        "I'm convinced every high growth B2B biz needs to run this content motion...\n\n" .
                        "Step 1: select subject matter experts (SMEs)\n" .
                        "- 3 types: employees, customers, external\n" .
                        "- who to pick? someone with a lived professional experience that your ICP would kill to learn from\n\n" .
                        "Step 2: interview SMEs\n" .
                        "- 60 minute interview\n" .
                        "- Follow this structure: big goal/challenge, hypothesis to reach/overcome it, playbook they executed, the results, timeless lessons from the experience\n\n" .
                        "Step 3: turn SME interview into longform playbook on-site\n" .
                        "- email gate it so you have a built-in mechanism to grow your newsletter list\n" .
                        "- link back to other playbooks/resources on your website when natural\n\n" .
                        "Step 4: create editorial newsletter for ICP\n" .
                        "- goal: be the go-to industry read for your ICP to succeed in their work\n" .
                        "- newsletter is a product, not just a marketing drip\n" .
                        "- condensed version of longform SME playbook is the headline story\n" .
                        "- other sections should all ladder up to the #1 stated goal of the newsletter\n\n" .
                        "Step 5: create derivative social content to drive distribution to site & newsletter\n" .
                        "- post from company & executive handles\n" .
                        "- while the social posts are meant to drive traffic to anchor content, they must have standalone, zero-click value\n\n" .
                        "Step 6: gather insights & make adjustments\n" .
                        "- what social content is driving greatest quality distribution?\n" .
                        "- what playbooks are driving largest increase in quality email subs?\n" .
                        "- what newsletters are driving most replies (trust), clicks (value), and demo requests (intent)?\n\n" .
                        "Step 7: make your pipeline very, very happy\n" .
                        "- short-term: quality top of funnel\n" .
                        "- mid-term: interest & trust in your content\n" .
                        "- long-term: primed to be your customer\n\n" .
                        "If you have any questions about the content motion, drop them below.\n\n" .
                        "Also, if you love the strategy, but it feels like too much work, storyarb runs this for some of the highest growth B2B companies in the world. hit us up",

                    'Example 2:' . "\n\n" .
                        "I have an 8-figure business idea hiding in plain sight. I'd build it, but don't have the energy at the moment.\n\n" .
                        "What’s the business? Stack Overflow for AI Prompts.\n\n" .
                        "…clears throat…\n\n" .
                        "Today, maximizing the value of AI depends almost entirely on how good you are at writing prompts — but crafting the right prompt is painful, slow, and full of trial and error.\n\n" .
                        "We're building a platform where AI users can find, share, and create high-performing prompts, powered by community voting, simple categorization and expert curation.\n\n" .
                        "… (rest of the full Post 2 here) …",

                    'Example 3:' . "\n\n" .
                        "Most founders should raise one round & plan to never raise again.\n\n" .
                        "People are calling it seedstrapping. Terrible name, but I like the idea.\n\n" .
                        "… (rest of the full Post 3 here) …",

                    'Example 4:' . "\n\n" .
                        "My team has been fighting to the death on social media for 5 days.\n\n" .
                        "And I couldn't be more jazzed about it.\n\n" .
                        "We created \"Own The Internet\"—a 10-week team LinkedIn competition—for 3 reasons:\n" .
                        "1) Create healthy competition that drives camaraderie & culture…\n\n" .
                        "… (rest of the full Post 4 here) …",

                    'Example 5:' . "\n\n" .
                        "We're running a company Hunger Games.\n\n" .
                        "Half-serious (employees are going head-to-head).\n" .
                        "Half-joking (arrows through the chest & bludgeoning not allowed).\n\n" .
                        "… (rest of the full Post 6 here) …",

                    'Example 6:' . "\n\n" .
                        "I’ve spoken to more software engineers in the last 5 weeks than the previous 5 years. Non-obvious takeaways I had…\n\n" .
                        "1) There were 2 core archetypes…\n\n" .
                        "… (rest of the full Post 7 here) …",

                    'Example 7:' . "\n\n" .
                        "I'm looking for a engineering intern for Distro.\n\n" .
                        "We've built an AI interviewer that turns conversation into content in minutes…\n\n" .
                        "… (rest of the full Post 8 here) …",

                    'Example 8:' . "\n\n" .
                        "Big announcement from Distro!\n\n" .
                        "You can now get interviewed about any piece of content on the internet…\n\n" .
                        "… (rest of the full Post 9 here) …",

                    'Example 9:' . "\n\n" .
                        "I’ve had limiting beliefs for as long as I can remember…\n" .
                        "High school: “I’m not attractive enough”\n" .
                        "College: “I’m not focused enough”\n" .
                        "Morning Brew: “I’m not smart enough”\n" .
                        "Post-Exit: “I got lucky”\n" .
                        "Today: “I can’t be an A+ entrepreneur, A+ partner, and A+ parent. Something has to give.”\n\n" .
                        "These beliefs used to consume me, but over time, I’ve developed a process to navigate them healthily.\n\n" .
                        "Step 1: Understand what a limiting belief is & why all humans have them\n\n" .
                        "Step 2: Bring awareness to my limiting beliefs & decide which to prioritize\n\n" .
                        "Step 3: Understand my options for facing them\n\n" .
                        "Step 4: Make a choice, be in the driver’s seat\n\n" .
                        "Check out today’s Founder’s Journal for the full breakdown: https://lnkd.in/dZsN6s3H",
                ],
                'Gary Vaynerchuk' => [
                    'Example 1:' . "\n\n" .
                        "Lack of emotion on where the attention will be tomorrow and super focus on where the attention is today." . "\n\n" .
                        "This is the foundation of how I think about #marketing." . "\n\n" .
                        "Add serious consideration to the types of content that do well on each platform." . "\n\n" .
                        "You’ve got a formula that works." . "\n\n" .
                        "Hope this makes sense and allows people to expand their horizons to new platforms! 💛💛💛💛",

                    'Example 2:' . "\n\n" .
                        "You’re in control!" . "\n\n" .
                        "All this video clip is saying is... you’re in control." . "\n\n" .
                        "And if you don’t believe it, I’m praying 🙏 this clip is the seed that starts your journey of figuring out that you’re lying to yourself." . "\n\n" .
                        "LIVE WITHIN YOUR MEANS AND WATCH YOUR HAPPINESS GO UP AND YOUR STRESS GO DOWN." . "\n\n" .
                        "Humility is a super power." . "\n\n" .
                        "Wait to the end… that’s the biggest truth that’s ever come out of my mouth 👄 …" . "\n\n" .
                        "This quick clip should get you “right” this Tuesday morning." . "\n\n" .
                        "Let’s learn how to budget and stop needing “stuff” to hide our insecurities." . "\n\n" .
                        "❣️❣️❣️❣️ Share with someone in your circle ⭕️ who needs this right now!" . "\n\n" .
                        "#motivation #Economy",

                    'Example 3:' . "\n\n" .
                        "College degrees work for some people and especially in the USA where many take on massive debt, it doesn’t work out at all." . "\n\n" .
                        "Please don’t get a degree for safety." . "\n\n" .
                        "Get one because you actually need it for the field you’re passionate about or if your government or parents pay for it." . "\n\n" .
                        "This video was from several years ago." . "\n\n" .
                        "The gap between the value of a degree and the safety net it provides has worsened since we filmed this." . "\n\n" .
                        "Make decisions that fit you and the reality of the world 🌎 we live in now." . "\n\n" .
                        "College degrees are great for many—but for some they are a massive setback financially and emotionally.",

                    'Example 4:' . "\n\n" .
                        "Too many focus on the “fancy stuff” too early!" . "\n\n" .
                        "From 18 to 32 you need to build the right habits." . "\n\n" .
                        "Pay your dues, build real relationships, and try new things." . "\n\n" .
                        "Deal with real-life step backs." . "\n\n" .
                        "Get your foundation right—like a great building needs steel and concrete." . "\n\n" .
                        "Don’t rush to the interior decorating that comes later." . "\n\n" .
                        "Take a deep breath and realize you’re just getting started." . "\n\n" .
                        "The world 🌎 will see the finished product later." . "\n\n" .
                        "Most of all, don’t build your life for others to look at—build it for you." . "\n\n" .
                        "🧱🧱🧱🏭🏦🏨🏗️⛩️🏛️🕋🏬🏚️🏟️🏙️",

                    'Example 5:' . "\n\n" .
                        "Brand is built in social..." . "\n\n" .
                        "Social is the primary way people consume content and thus the biggest business brand and sales opportunities sit on these 6–10 platforms." . "\n\n" .
                        "Fortune 500 companies continue to under-allocate funds and expertise on these platforms." . "\n\n" .
                        "Small businesses and individuals continue to underestimate the sheer power of free organic reach." . "\n\n" .
                        "Please double down on social content in 2025 for those who are ambitious and hungry!",

                    'Example 6:' . "\n\n" .
                        "Kind intent always wins in the end ❤️" . "\n\n" .
                        "I’m not even talking politics—that’s a whole other thing." . "\n\n" .
                        "I’m talking day to day—there’s so much testiness and overreaction." . "\n\n" .
                        "We need a deep collective breath and to realize the other person isn’t against you." . "\n\n" .
                        "Please start loving yourself more so you can love others more." . "\n\n" .
                        "We need more humility." . "\n\n" .
                        "The idea that your perspective is 100% correct is laughable." . "\n\n" .
                        "Lean into love, humility, and find capacity for others!",

                    'Example 7:' . "\n\n" .
                        "Building a business is hard..." . "\n\n" .
                        "And what makes it hard is everything that happens every day at every moment." . "\n\n" .
                        "That's why I talk a lot about self-awareness and loving the process." . "\n\n" .
                        "When you do something for money, you quit. When you do it because you love it, you do it forever.",

                    'Example 8:' . "\n\n" .
                        "Posting on social networks is free..." . "\n\n" .
                        "this is gary vaynerchuk" . "\n\n" .
                        "There is no other advertising medium in the world that is free." . "\n\n" .
                        "Yes, you can run paid ads, but organic posts cost nothing." . "\n\n" .
                        "So many of you are missing out." . "\n\n" .
                        "You may be comfortable on 1 or 2 platforms." . "\n\n" .
                        "My goal is for you to take 4, 5, 6, 7 platforms seriously." . "\n\n" .
                        "Every platform you ignore is a missed opportunity." . "\n\n" .
                        "At the end of the day, it creates demand for your business:" . "\n\n" .
                        "- Selling deli sandwiches" . "\n\n" .
                        "- Sneakers" . "\n\n" .
                        "- Babysitting gigs" . "\n\n" .
                        "- Selling records" . "\n\n" .
                        "Leaving free opportunities on the table is nonsense.",

                    'Example 9:' . "\n\n" .
                        "Fear kills growth..." . "\n\n" .
                        "Here are 3 ways to overcome fear of failure:" . "\n\n" .
                        "1. Stop worrying about other people's opinions." . "\n\n" .
                        "2. Give the future more credit than the past." . "\n\n" .
                        "3. Redefine what it means to \"win.\"" . "\n\n" .
                        "LinkedIn—what else would you add to this list?",

                    'Example 10:' . "\n\n" .
                        "You're not lazy—you just don't love what you do..." . "\n\n" .
                        "Please understand this, y’all…" . "\n\n" .
                        "You need to fight for the thing you love." . "\n\n" .
                        "The one thing I want most for you is the feeling you get when you do what you love, day in and day out." . "\n\n" .
                        "Because when you truly love it, you’ll work every single second for it." . "\n\n" .
                        "You can only go hard if you love what you do—otherwise you’ll crash and burn." . "\n\n" .
                        "Many burn out because:" . "\n\n" .
                        "- They don’t love it" . "\n\n" .
                        "- They did it for the clout" . "\n\n" .
                        "- They did it for the bag" . "\n\n" .
                        "- They did it for the fame" . "\n\n" .
                        "They DIDN’T DO IT FOR THE GAME." . "\n\n" .
                        "When you love the game, it gives you energy—it doesn’t strip it."
                ],
                'Ann Handley' => [
                    'Example 1:' . "\n\n" .
                        "There's a lot of hoopla around AI." . "\n\n" .
                        "And a lot of questions around it, as my post yesterday pointed out." . "\n\n" .
                        "But what's actually going on?" . "\n\n" .
                        "How are top-performing brands *actually* using AI if their goal is to deliver a stellar customer experience?" . "\n\n" .
                        "Turn-down service...?" . "\n\n" .
                        "Meal-prepping for us...?" . "\n\n" .
                        "No. Or not yet. (We can only hope.)" . "\n\n" .
                        "Right now, the top-performing brands are using AI mostly to experiment & to elevate customer interactions in these 3 ways, according to brand new research:" . "\n\n" .
                        "🔮 AI-powered predictive analytics, such as identifying customers likely to churn" . "\n\n" .
                        "🖼️ AI-generated images for messaging campaigns" . "\n\n" .
                        "✅ To QA content for brand consistency" . "\n\n" .
                        "Those are the self-identifying top performers—the ones who raised their hands to say they are **killing it** at Customer Experience." . "\n\n" .
                        "Here are more specifics on both the top performers (and the laggards!) in this Braze report." . "\n\n" .
                        "They talked to 2,300 marketing execs (so we don't have to). (LOL)" . "\n\n" .
                        ">>> https://lnkd.in/eq6anHcF <<<",

                    'Example 2:' . "\n\n" .
                        "Meta used all 3 of my books & millions of other books, ebooks, and research papers to train its AI." . "\n\n" .
                        "All without consent, compensation, copyright concern, credit." . "\n\n" .
                        "You find out only by searching a database published a few days ago by The Atlantic." . "\n\n" .
                        "It's sketchy AF. I’ll tell you why in a sec. But first… should we care?" . "\n\n" .
                        "If you’re on the list, you have one of two reactions:" . "\n\n" .
                        "🥳 Honored! Flattered! My work is worthy!" . "\n\n" .
                        "😳 Ummm WTH. What about consent, compensation, etc.?" . "\n\n" .
                        "More broadly: Should you care whether Meta used 72 books from David Sedaris? 200 from Margaret Atwood? The entire library of everyone everywhere?" . "\n\n" .
                        "What’s missing in this conversation is that Meta intentionally made *a choice*... as the kids say." . "\n\n" .
                        "They actively chose to steal the books, instead of going through the proper & legal channels, because the legal way was too slow." . "\n\n" .
                        "TOO SLOW." . "\n\n" .
                        "That’s problematic, isn’t it? Look, I use AI. I see its value. But the scale of this is nuts. And gross." . "\n\n" .
                        "If Llama 3 was to compete, it needed to be trained on a huge amount of high-quality writing – books, not Instagram captions or LinkedIn posts." . "\n\n" .
                        "Acquiring all of that text legally could take time." . "\n\n" .
                        "“Yo ho ho! Should we pirate it instead?” they wondered." . "\n\n" .
                        "“Abso-tooting-lootly,” they said—“Let’s loot all the books!”" . "\n\n" .
                        "So they went to LibGen, a pirate library with more than 7.5 million books and 81 million research papers." . "\n\n" .
                        "They looted and rolled around in all the words like pirates rolling in gold doubloons." . "\n\n" .
                        "So yes, it’s problematic:" . "\n\n" .
                        "🏴‍☠️ We’re normalizing the theft of IP to an absurd degree." . "\n\n" .
                        "🏴‍☠️ Gen AI often presents like all-knowing oracles, uncoupled from sources." . "\n\n" .
                        "🏴‍☠️ It’s not colorless, odorless “data.” It’s words that make up sentences that make up paragraphs that make up pages that writers have created." . "\n\n" .
                        "Clarity, consent, compensation. Is it too much to ask that AI companies be transparent about data sources and use, obtain permission from creators, and provide fair payment for using their works?" . "\n\n" .
                        "Just me?",

                    'Example 3:' . "\n\n" .
                        "Is messaging the new email...?" . "\n\n" .
                        "Braze’s Global Customer Engagement Review found 43% of marketing execs are exploring messaging apps (WhatsApp, LINE) to connect personally with customers." . "\n\n" .
                        "85% of marketers worry their messages are missing the mark." . "\n\n" .
                        "Brands exceeding revenue goals? 60% said they were \"extremely concerned\" about connecting with customers." . "\n\n" .
                        "My mom was right: It pays to worry." . "\n\n" .
                        "See the data and tell me if your experience matches Braze's takeaways." . "\n\n" .
                        ">>> https://lnkd.in/ecZwwT2V <<<",

                    'Example 4:' . "\n\n" .
                        "🧟‍♂️ Today's Tuesday Tip: Avoid passive voice using the \"by zombies\" test." . "\n\n" .
                        "If you can add \"by zombies\" after the verb and it still makes sense, it’s passive." . "\n\n" .
                        "PASSIVE: The white paper was written (by zombies) ✓" . "\n\n" .
                        "ACTIVE: I wrote the white paper (by zombies) ✗" . "\n\n" .
                        "Passive voice feels dead—sentences shuffle like overmedicated zombies." . "\n\n" .
                        "Active voice is alive: a flicker of life instead of hollow zombie eyes." . "\n\n" .
                        "*Note: Always credit your sources.*" . "\n\n" .
                        "P.S. Can we bold or underline within posts? I’m forever confounded.",

                    'Example 5:' . "\n\n" .
                        "Marketing, Creative, Agency friends: I need your expertise." . "\n\n" .
                        "Cella’s annual survey is open—how marketing & creative work gets done in the age of AI + uncertainty." . "\n\n" .
                        "Topics: 🥓 work process, 🧘 brand positioning, 👯 staffing models, 🤖 AI impact + more." . "\n\n" .
                        "Take a few minutes to lend your experience; get the full report when ready." . "\n\n" .
                        ">>> https://lnkd.in/eFGZy_uc <<<" . "\n\n" .
                        "P.S. Cella is giving away a free consulting session to one lucky respondent.",

                    'Example 6:' . "\n\n" .
                        "👀 THIS IS THE SIGN YOU’RE LOOKING FOR 👀" . "\n\n" .
                        "Stop Playing Small and do Bigger Things in 2025!" . "\n\n" .
                        "Raise your hand to speak at MarketingProfs B2B Forum—Boston, 2025." . "\n\n" .
                        "I played small until 2009—took myself by the sweaty-palmed hand and leapt." . "\n\n" .
                        "One of the scariest and best risks I ever took." . "\n\n" .
                        "Submit your session by 1/31/2025:" . "\n\n" .
                        ">>> https://lnkd.in/eBHdDxhm <<<" . "\n\n" .
                        "See you in Boston!",

                    'Example 7:' . "\n\n" .
                        "Just got a Simplified Chinese translation of “Everybody Writes 2”—very cool! 🥰" . "\n\n" .
                        "I asked AI to translate the subtitle; I hope it’s not literally “high-grip” writing and “big support” content." . "\n\n" .
                        "AI can be unreliable (or drunk on eggnog). (*hic!*)" . "\n\n" .
                        "But sweet sugarplums, I kind of love it." . "\n\n" .
                        "If you know Chinese, let me know how accurate it is!" . "\n\n" .
                        "PPS Happy holidays and may 2025 content be hit copy, big support, and fast frame speed. 🥂 CHEERS!",

                    'Example 8:' . "\n\n" .
                        "Next-level conference networking pro tip: Create a QR code with your contact info & tattoo it on your body. 🤘" . "\n\n" .
                        "I met Wesley Butstraen at BAM Marketing Congress in Brussels—this is his arm." . "\n\n" .
                        "1,850 attendees, but only one QR-coded arm." . "\n\n" .
                        "Marketing is all about standing out, isn’t it?",

                    'Example 9:' . "\n\n" .
                        "🥳 FUN NEWS! 🥳 The MarketingProfs B2B Forum kicks off today in Boston—and we are officially SOLD OUT!" . "\n\n" .
                        "In-person events are SO BACK amidst AI, remote work, webinars, social media…" . "\n\n" .
                        "We crave real high-fives and hugs." . "\n\n" .
                        "If you’re here—thank you! Can’t wait to see you!" . "\n\n" .
                        "If you’re not—more tickets next year! 😉" . "\n\n" .
                        "#mpb2b #marketingprofs #b2bmarketing #EventsAreBack",

                    'Example 10:' . "\n\n" .
                        "PS Here’s a photo I took during set-up last night—I’m obsessed with our bird logo perched perfectly." . "\n\n" .
                        "Attention to detail matters everywhere at this event." . "\n\n" .
                        "I hope people feel the love and that it feels special to you, too. 🫶🏻"
                ],
                'Steven Bartlett' => [
                    'Example 1:' . "\n\n" .
                        "\"YOU NEED TO FAIL MORE\".... This is the first time we've let the world see inside what we do!" . "\n\n" .
                        "If you want me to send you access to a tool we've built internally to grow shows, podcasts, YouTube, comment \"Send me the tool!\" and I'll DM it to you shortly!" . "\n\n" .
                        "(Currently not available to the public)." . "\n\n" .
                        "Lastly, if you've got any questions about building a podcast, a YouTube channel or anything else you see in this video, please let me know and I'll do my best to answer below!" . "\n\n" .
                        "You can go watch the full video over on the Forbes YouTube channel!" . "\n\n" .
                        "FLIGHTSTORY // The Diary Of A CEO",

                    'Example 2:' . "\n\n" .
                        "Today’s guest on The Diary Of A CEO believes that building wealth is like climbing a mountain." . "\n\n" .
                        "Investing is the steady ascent and retirement is the summit." . "\n\n" .
                        "Welcome back Morgan Housel!" . "\n\n" .
                        "Morgan is a financial maestro whose insights cut through modern economics to the heart of what matters: happiness, control, and freedom." . "\n\n" .
                        "Last time, he covered key principles to building wealth, the value of frugality and patience, and how humility preserves wealth." . "\n\n" .
                        "In this interview, we navigate uncertainty and the threat of a global trade war by emphasising the power of compound interest over long-term horizons." . "\n\n" .
                        "We cover:" . "\n\n" .
                        "- Why you’ll regret buying a house?" . "\n\n" .
                        "- The dumbest money advice that people still believe…" . "\n\n" .
                        "- The $30 trillion lie holding America together!" . "\n\n" .
                        "- How robots are replacing the middle class…" . "\n\n" .
                        "- The benefits of independence." . "\n\n" .
                        "So many golden nuggets that will leave you with a lot to think about!" . "\n\n" .
                        "Watch the full episode now on YouTube: Search “The Diary of a CEO Morgan Housel” or click the link in the comments.",

                    'Example 3:' . "\n\n" .
                        "🚨 I've spoken to a few people at Meta over the last few weeks, and nobody has been able to stop it." . "\n\n" .
                        "So you're going to have to protect yourself... read on." . "\n\n" .
                        "Over the last few weeks, hundreds of deepfake ads have popped up on Meta and X promoting scams, using AI-made videos or images of me." . "\n\n" .
                        "Some ideas on how to stay safe in a deepfake world:" . "\n\n" .
                        "✅ 1. Set a family/company “code word” — agree on a phrase that must appear in any urgent money or password request before you act." . "\n\n" .
                        "✅ 2. Double-check every transfer on a second channel — if a message or call asks for cash, phone the sender back on a number you already trust." . "\n\n" .
                        "✅ 3. Teach loved ones the red flags — cloned voices or videos always create pressure: “right now”, secrecy, emergencies. Pause, breathe and verify." . "\n\n" .
                        "✅ 4. Limit your digital footprint — lock social accounts if you can, avoid posting long voice notes or high-res videos that give scammers free training data." . "\n\n" .
                        "✅ 5. Report and block fake ads immediately — every time you spot a deepfake on Meta or X, tap “Report ad” so others don't fall for it." . "\n\n" .
                        "If you have any other tips or thoughts, please share them below... I'll be talking more about this on The Diary Of A CEO!",

                    'Example 4:' . "\n\n" .
                        "Are remote-first companies setting up young employees to fail???" . "\n\n" .
                        "Once a week I email 100 of the world's top CEOs." . "\n\n" .
                        "I ask them the one question you desperately need an answer to." . "\n\n" .
                        "I send that answer straight to your inbox, for free!" . "\n\n" .
                        "Go to 100CEOS(dot)com to join hundreds of thousands of people who receive this email once a week! ❤️" . "\n\n" .
                        "Next week's question is extremely interesting (and personal)." . "\n\n" .
                        "You can follow this week's selected CEOs here: Codie A. Sanchez, Mark Bailie, Daniel Priestley, Nicola Kilner." . "\n\n" .
                        "Link to sign up is in the comments!",

                    'Example 5:' . "\n\n" .
                        "This is the most important conversation I don’t see enough people having right now (other than my friends in Silicon Valley)… 👇🏾" . "\n\n" .
                        "🤖 10 things business owners and professionals should be doing now:" . "\n\n" .
                        "✅ Treat your data like an asset class — clean it, label it, and keep it in-house." . "\n\n" .
                        "✅ Build a culture of endless small experiments — speed of iteration and learning will separate winners from losers." . "\n\n" .
                        "✅ Double down on EQ skills: negotiation, coaching, storytelling." . "\n\n" .
                        "✅ Recalculate head-count and org chart assuming agents handle 60–70% of routine tasks." . "\n\n" .
                        "✅ Automate personal drudgery — meeting booking, expense filing." . "\n\n" .
                        "✅ Learn to prompt: write goals (objective, constraints, success metric) in ≤3 lines." . "\n\n" .
                        "✅ Prototype an agent-driven version of your single most time-intensive process within 30 days." . "\n\n" .
                        "✅ Share what you learn with a group — we do this in Slack at FlightStory." . "\n\n" .
                        "✅ Share metrics and wins — measure hours saved or revenue gained per £ spent on agents." . "\n\n" .
                        "Let me know if you have any questions or thoughts... 👇🏾",

                    'Example 6:' . "\n\n" .
                        "What if the key to mastering your life lies not in controlling the world around you, but in mastering the world within you?" . "\n\n" .
                        "Today’s guest will help you do exactly that." . "\n\n" .
                        "I sat down for hours with Master Shi Heng Yi, a Shaolin master who’s spent 36 years training body, mind, and spirit." . "\n\n" .
                        "This episode isn’t just about monks or martial arts — it’s about everyday people." . "\n\n" .
                        "Whether you’re burnt out, overwhelmed, or trying to get your life together, this conversation will give you tools to overcome those problems." . "\n\n" .
                        "We cover:" . "\n\n" .
                        "- The truth about why most people are miserable." . "\n\n" .
                        "- The dark side of discipline no one talks about." . "\n\n" .
                        "- The five mental states blocking your mind." . "\n\n" .
                        "- How he controls his mind and why you need his tricks." . "\n\n" .
                        "- Why your goals are sabotaging your happiness." . "\n\n" .
                        "It’s about becoming more of who you could be." . "\n\n" .
                        "Let me know what hits home for you after watching 👇",

                    'Example 7:' . "\n\n" .
                        "💥 Influencers and creators are no longer just ad space — they’re entrepreneurs, and AI changes everything." . "\n\n" .
                        "Phase 1: Influencers became ad space — paid billboards." . "\n\n" .
                        "Phase 2: Influencers became employees — longer brand contracts and creative roles." . "\n\n" .
                        "Phase 3: Influencers became equity partners — aligning incentives with upside." . "\n\n" .
                        "Phase 4: Influencers became entrepreneurs — full-fledged businesses like MrBeast’s Feastables." . "\n\n" .
                        "AI will accelerate this — one person can now launch, market, and produce without a team." . "\n\n" .
                        "At FLIGHTSTORY we’re building creator-led shows and the commercial ecosystem around them." . "\n\n" .
                        "Please join me in welcoming our next hire, Leon Farrell, to the team!",

                    'Example 8:' . "\n\n" .
                        "Is ChatGPT making everyone feel really inauthentic???" . "\n\n" .
                        "Am I speaking to you... or your AI?" . "\n\n" .
                        "Old emails: “hi mate, quick one…”" . "\n\n" .
                        "New emails: “Greetings Steven, I trust this email finds you well as we forge ahead…”" . "\n\n" .
                        "If AI improves your grammar but destroys your authenticity, you’re better off without it." . "\n\n" .
                        "Humans make mistakes — that’s what makes them human." . "\n\n" .
                        "The best communicators will speak without AI." . "\n\n" .
                        "Use AI for spell-check, but when it matters — speak for yourself!",

                    'Example 9:' . "\n\n" .
                        "What if you could understand someone’s thoughts without them saying a word?" . "\n\n" .
                        "Joe Navarro, former FBI agent and body language expert, joins us on The Diary Of A CEO to reveal silent signals." . "\n\n" .
                        "He specialised in counterintelligence and helped establish the FBI’s Behavioural Analysis Program." . "\n\n" .
                        "Now he advises professionals on using behavioural analysis for negotiation and confidence." . "\n\n" .
                        "We cover:" . "\n\n" .
                        "- A narcissist’s favourite trick to control you." . "\n\n" .
                        "- A habit that makes you weak!" . "\n\n" .
                        "- How to read nonverbal communication." . "\n\n" .
                        "- The FBI trick to spot a lie in three seconds." . "\n\n" .
                        "Transform your personal and professional life by mastering the silent language." . "\n\n" .
                        "Watch on YouTube: Search “The Diary of a CEO Joe Navarro” or click the link in comments.",

                    'Example 10:' . "\n\n" .
                        "Today we’re on Forbes — which is insane!" . "\n\n" .
                        "To answer the headline... why did we reject a major deal???" . "\n\n" .
                        "Last year, we were offered a major podcasting deal — the kind many peers take." . "\n\n" .
                        "But it didn’t feel like the right path for us." . "\n\n" .
                        "Why we turned it down:" . "\n\n" .
                        "🚩 At least 300% more ads, which our listeners don’t want." . "\n\n" .
                        "🚩 Less control over where the show appears — we value independence and accessibility." . "\n\n" .
                        "🚩 Headline numbers aren’t what they seem — deals are spread over years and tied to targets." . "\n\n" .
                        "Our CRO modelled the numbers and believes we can do better on our own." . "\n\n" .
                        "We have nearly 100 full-time people building FLIGHTSTORY in-house." . "\n\n" .
                        "Betting on ourselves is a decision I can live with." . "\n\n" .
                        "Thanks for being on the journey with us!"
                ],
                'Lara Acosta' => [
                    'Example 1:' . "\n\n" .
                        "I hit $100k months before I turned 27 (here's how)." . "\n\n" .
                        "Step 1: I stopped trying to be an entrepreneur." . "\n\n" .
                        "Stopped trying to hire a 'team' to grow." . "\n\n" .
                        "Stopped trying to 'look' cool and have an office." . "\n\n" .
                        "Instead, I kept it ridiculously simple:" . "\n\n" .
                        "• Started creating content about what I did best." . "\n\n" .
                        "• Marketed it as the best solution for people." . "\n\n" .
                        "• Used content to 10x my distribution." . "\n\n" .
                        "This is the creator-led marketing approach." . "\n\n" .
                        "But instead of selling someone else's product," . "\n\n" .
                        "I built and sold my own. High profit, low cost." . "\n\n" .
                        "Used storytelling to connect with strangers." . "\n\n" .
                        "Used authority content to gain their trust fast." . "\n\n" .
                        "Used technical content to prove I could help them." . "\n\n" .
                        "Built a following through content." . "\n\n" .
                        "Monetised through different offers." . "\n\n" .
                        "Sold agency services, coaching and consulting." . "\n\n" .
                        "Then built courses and programmes around them." . "\n\n" .
                        "A full-stacked 'offer' suite that brings in thousands." . "\n\n" .
                        "Simplicity scales faster." . "\n\n" .
                        "You don't need to hire a team or have an office to make money online anymore." . "\n\n" .
                        "Just good content and an offer." . "\n\n" .
                        "PS: Check out this report by Kajabi with the full breakdown on how to do it this year." . "\n\n" .
                        "I loved it: https://lnkd.in/eYb-kTUG" . "\n\n" .
                        "PPS: I actually built a GPT for you to get a full personalised breakdown." . "\n\n" .
                        "Comment 'creator' and I'll send it to you :)",

                    'Example 2:' . "\n\n" .
                        "I don't believe in work-life balance as an entrepreneur." . "\n\n" .
                        "I believe in seasons of deep work and deep rest." . "\n\n" .
                        "To become a high performer, you should do both." . "\n\n" .
                        "Months of deep focus on a project." . "\n\n" .
                        "Weeks of deep rest to re-focus." . "\n\n" .
                        "After building a successful career, here's how I do it:" . "\n\n" .
                        "1. Make your business (and purpose) your focus." . "\n\n" .
                        "2. Set a 60–90-day ambitious goal for it." . "\n\n" .
                        "3. Have 3×90-minute work blocks daily." . "\n\n" .
                        "Focus on that only for the next 3 months." . "\n\n" .
                        "No distractions. No multi-tasking..." . "\n\n" .
                        "Just good ideas and immediate execution." . "\n\n" .
                        "It's simple, but 99% of people don't do it." . "\n\n" .
                        "It's obsessive, but all athletes do it." . "\n\n" .
                        "So why not entrepreneurs?" . "\n\n" .
                        "Immerse yourself in your craft." . "\n\n" .
                        "Do it for 6 weeks, watch your life change completely." . "\n\n" .
                        "PS: If you're ready to take 6 weeks seriously and deep focus with me..." . "\n\n" .
                        "I've got something for you." . "\n\n" .
                        "Launching 05.05.25: https://lnkd.in/eC_ds6Pn",

                    'Example 3:' . "\n\n" .
                        "I don't believe in work-life balance as an entrepreneur." . "\n\n" .
                        "I believe in seasons of deep work and deep rest." . "\n\n" .
                        "To become a high performer, you should do both." . "\n\n" .
                        "Months of deep focus on a project." . "\n\n" .
                        "Weeks of deep rest to re-focus." . "\n\n" .
                        "After building a successful career, here's how I do it:" . "\n\n" .
                        "1. Make your business (and purpose) your focus." . "\n\n" .
                        "2. Set a 60–90-day ambitious goal for it." . "\n\n" .
                        "3. Have 3×90-minute work blocks daily." . "\n\n" .
                        "Focus on that only for the next 3 months." . "\n\n" .
                        "No distractions. No multi-tasking..." . "\n\n" .
                        "Just good ideas and immediate execution." . "\n\n" .
                        "It's simple, but 99% of people don't do it." . "\n\n" .
                        "It's obsessive, but all athletes do it." . "\n\n" .
                        "So why not entrepreneurs?" . "\n\n" .
                        "Immerse yourself in your craft." . "\n\n" .
                        "Do it for 6 weeks, watch your life change completely." . "\n\n" .
                        "PS: If you're ready to take 6 weeks seriously and deep focus with me..." . "\n\n" .
                        "I've got something for you." . "\n\n" .
                        "Launching 05.05.25: https://lnkd.in/eC_ds6Pn",

                    'Example 4:' . "\n\n" .
                        "The 2025 LinkedIn Creator Founder Blueprint." . "\n\n" .
                        "Everyone asks me \"what the future of LinkedIn is.\"" . "\n\n" .
                        "My answer? One thing." . "\n\n" .
                        "The future of LinkedIn is you, but..." . "\n\n" .
                        "It's not enough to 'just post'." . "\n\n" .
                        "It's not enough to 'go viral'." . "\n\n" .
                        "LinkedIn is changing, but not in a bad way." . "\n\n" .
                        "Even small creators are winning." . "\n\n" .
                        "In this live I'm breaking it down for the very first time." . "\n\n" .
                        "I'm bringing two creators who've mastered this, and I've watched it all." . "\n\n" .
                        "Join us?" . "\n\n" .
                        "PS: Join the waitlist for my new programme dropping soon: https://lnkd.in/e9qZmjU2",

                    'Example 5:' . "\n\n" .
                        "I just mapped out your next 365 days of LinkedIn growth:" . "\n\n" .
                        "(Save this so you can use it later)" . "\n\n" .
                        "If you want to:" . "\n\n" .
                        "- Grow by +50k every year." . "\n\n" .
                        "- Make at least $100,000 from LinkedIn." . "\n\n" .
                        "- Become an authority in your space." . "\n\n" .
                        "Follow this simple strategy for the next 365 days." . "\n\n" .
                        "Your commitments:" . "\n\n" .
                        "1. At least 3× posts a week." . "\n\n" .
                        "2. Commenting for 60 mins a day." . "\n\n" .
                        "3. Committing to consistent outstanding content." . "\n\n" .
                        "The strategy:" . "\n\n" .
                        "1. Find trending topics you can use for your brand." . "\n\n" .
                        "2. Reverse-engineer viral content on your feed." . "\n\n" .
                        "3. Create versions of it for the first 3 months." . "\n\n" .
                        "4. Take the best 10–15 performing ones." . "\n\n" .
                        "5. Recreate that for every new post." . "\n\n" .
                        "The growth accelerator method:" . "\n\n" .
                        "1. Use any social proof you have in 80% of your posts." . "\n\n" .
                        "   ↳ \"After working with Fortune 500 companies...\"" . "\n\n" .
                        "   ↳ \"I've built a $5k/mo business doing this...\"" . "\n\n" .
                        "   ↳ \"I just had a call with my dream client...\"" . "\n\n" .
                        "2. Have a signature style for your content." . "\n\n" .
                        "   ↳ Posting photos of you in the same place." . "\n\n" .
                        "   ↳ Carousels with the same cover every time." . "\n\n" .
                        "   ↳ A Twitter screenshot with a polarising hook." . "\n\n" .
                        "3. Algorithm proofing and content flywheel." . "\n\n" .
                        "   ↳ You have a style that works." . "\n\n" .
                        "   ↳ People know it's you before reading your name." . "\n\n" .
                        "   ↳ You repeat that format every week." . "\n\n" .
                        "Bonus hack: Use trend-jacking immediately." . "\n\n" .
                        "Stick to this for 365 days and watch your life change." . "\n\n" .
                        "Repost this ♻️ so everyone can see it!" . "\n\n" .
                        "Want help building your 365-day strategy? Something is coming... https://lnkd.in/eC_ds6Pn",

                    'Example 6:' . "\n\n" .
                        "I failed — I thought my career was over." . "\n\n" .
                        "No one bought my first ever programme." . "\n\n" .
                        "It was my first ever product." . "\n\n" .
                        "All the time spent felt like a waste. I was embarrassed." . "\n\n" .
                        "The pressure was unbearable. I nearly called it all off." . "\n\n" .
                        "But then I realised I had two options:" . "\n\n" .
                        "Complain or create — I chose to create." . "\n\n" .
                        "24 hours later, I hit $12k from it. Here's how:" . "\n\n" .
                        "1. Figured out why no one bought." . "\n\n" .
                        "2. Realised it was a tech issue with Stripe." . "\n\n" .
                        "3. Reached out to people who showed interest." . "\n\n" .
                        "4. Posted three times more to regain traffic." . "\n\n" .
                        "A lesson for you: when you fail, move quickly." . "\n\n" .
                        "Find the problem, get help, and take action." . "\n\n" .
                        "Pressure is a privilege; use it right." . "\n\n" .
                        "People who win aren't the smartest." . "\n\n" .
                        "They just have a higher tolerance for failure." . "\n\n" .
                        "Your failures don't define you." . "\n\n" .
                        "The way you respond does." . "\n\n" .
                        "PS: Tell me a recent failure, I'll tell you how to fix it in the comments — ready? Go!" . "\n\n" .
                        "Repost this ♻️ so we can all win together this year." . "\n\n" .
                        "We're 11 days out from releasing my new project... join the waitlist: https://lnkd.in/eC_ds6Pn",

                    'Example 7:' . "\n\n" .
                        "I've taught viral storytelling to thousands this year." . "\n\n" .
                        "They were all held back by the same myth:" . "\n\n" .
                        "“I have nothing to share.” Most said they had nothing to share at first." . "\n\n" .
                        "They felt they weren't interesting enough." . "\n\n" .
                        "They felt they didn't have the credentials." . "\n\n" .
                        "They felt insecure and embarrassed too." . "\n\n" .
                        "But then I remind them:" . "\n\n" .
                        "I was a broke student with \"nothing\" to share." . "\n\n" .
                        "So I documented my journey instead." . "\n\n" .
                        "Go from nothing to share, to everything to document." . "\n\n" .
                        "Storytelling isn't just about the story." . "\n\n" .
                        "It's about how you tell your story." . "\n\n" .
                        "Your competitors get ahead because they write better (for now)." . "\n\n" .
                        "Stop optimizing your strategy — optimize your story." . "\n\n" .
                        "Bonus tip: The best stories start at the struggle or success — that’s the hook!" . "\n\n" .
                        "Storytelling masterclass, over 🫳🏼🎤" . "\n\n" .
                        "P.S: On May 5th I'm dropping the accelerator to get you content FAST." . "\n\n" .
                        "Join 1k+ entrepreneurs: https://lnkd.in/eC_ds6Pn",

                    'Example 8:' . "\n\n" .
                        "The best piece of business advice you'll hear today:" . "\n\n" .
                        "(If you're feeling lost on LinkedIn, read this)" . "\n\n" .
                        "Most entrepreneurs don't need to do more." . "\n\n" .
                        "They actually need to do less. I know I did." . "\n\n" .
                        "I found one thing that worked, and I left it behind." . "\n\n" .
                        "We fall into the trap of chasing every new model." . "\n\n" .
                        "But you don't need that." . "\n\n" .
                        "You just need the one thing that drives results." . "\n\n" .
                        "To any entrepreneur feeling lost:" . "\n\n" .
                        "Everyone else is too — key is building that one thing." . "\n\n" .
                        "Success and money will follow." . "\n\n" .
                        "Love: someone going back to her one thing." . "\n\n" .
                        "Join the waitlist: https://lnkd.in/eC_ds6Pn",

                    'Example 9:' . "\n\n" .
                        "My 4-step writing framework is unbeatable." . "\n\n" .
                        "(If you're stuck at low likes and impressions, read this)" . "\n\n" .
                        "Everyone talks about the algorithm changing." . "\n\n" .
                        "But small creators go viral using this hack." . "\n\n" .
                        "I call it the SLAY Framework:" . "\n\n" .
                        "1. The first 3 lines should be a story." . "\n\n" .
                        "2. On line 4, lead with a lesson." . "\n\n" .
                        "3. Add actionable advice (listicles)." . "\n\n" .
                        "4. Finish with a question." . "\n\n" .
                        "Storytelling is the ultimate weapon on LinkedIn." . "\n\n" .
                        "Focus on emotions over facts." . "\n\n" .
                        "They'll remember you forever." . "\n\n" .
                        "Bonus: Think lesson first, story second." . "\n\n" .
                        "Your goal is to educate with stories." . "\n\n" .
                        "♻️ Repost this to make storytelling easy for everyone." . "\n\n" .
                        "Join waitlist: https://lnkd.in/eC_ds6Pn",

                    'Example 10:' . "\n\n" .
                        "2018: We regret to inform you..." . "\n\n" .
                        "2025: We’re delighted to inform you..." . "\n\n" .
                        "This happened when I went all in on LinkedIn." . "\n\n" .
                        "Posted every week for 52 weeks straight." . "\n\n" .
                        "Learned to write, sell, and market myself." . "\n\n" .
                        "Today the companies I applied to reach back out every month. No cover letter needed." . "\n\n" .
                        "When you create content here, you're building at scale." . "\n\n" .
                        "You become known, liked, and trusted faster than ever." . "\n\n" .
                        "Stop relying on a CV or paid ad." . "\n\n" .
                        "Rely on yourself and your expertise every day." . "\n\n" .
                        "Start creating content about you. You won't regret it." . "\n\n" .
                        "P.S: I'm dropping something tomorrow... be the first to know: https://lnkd.in/eC_ds6Pn"
                ],
                'Justin Welsh' => [
                    'Example 1:' . "\n\n" .
                        "Most people wait for the perfect moment." . "\n\n" .
                        "But here's the truth:" . "\n\n" .
                        "Perfect moments don't exist." . "\n\n" .
                        "Perfect conditions don't exist." . "\n\n" .
                        "Perfect plans don't exist." . "\n\n" .
                        "The most successful people in business don't wait." . "\n\n" .
                        "They build through consistent small actions." . "\n\n" .
                        "A post." . "\n\n" .
                        "A sentence." . "\n\n" .
                        "A sketch." . "\n\n" .
                        "A new belief." . "\n\n" .
                        "This is how real work happens." . "\n\n" .
                        "Not through some huge effort." . "\n\n" .
                        "Through small steps that compound over time." . "\n\n" .
                        "The question isn't \"how do I create something massive?\"" . "\n\n" .
                        "It's \"what small thing can I build today?\"" . "\n\n" .
                        "Start by building here on this platform." . "\n\n" .
                        "- A better profile" . "\n\n" .
                        "- Better content" . "\n\n" .
                        "- Smarter openers" . "\n\n" .
                        "- Better calls-to-action" . "\n\n" .
                        "- A better system" . "\n\n" .
                        "Here's my 90-minute operating system you can set up: https://lnkd.in/eh9pVVuf" . "\n\n" .
                        "Watch it and start making impactful small changes today.",

                    'Example 2:' . "\n\n" .
                        "I see too many people doing work they hate." . "\n\n" .
                        "Building businesses they resent." . "\n\n" .
                        "Chasing metrics that don't matter." . "\n\n" .
                        "Following paths others designed." . "\n\n" .
                        "Why?" . "\n\n" .
                        "Because they've already invested the time." . "\n\n" .
                        "Here's how to break free:" . "\n\n" .
                        "1. Your next decade is better than your last decade" . "\n\n" .
                        "2. Opportunity cost is better than sunk cost" . "\n\n" .
                        "3. Growth is better than comfort" . "\n\n" .
                        "The business you want exists on the other side of letting go." . "\n\n" .
                        "Ready to build a business that sets you free?" . "\n\n" .
                        "→ No more trading time for money" . "\n\n" .
                        "→ No more answering to anyone" . "\n\n" .
                        "→ No more Sunday night dread" . "\n\n" .
                        "→ No more \"what if\" regrets" . "\n\n" .
                        "6,000+ people have already escaped." . "\n\n" .
                        "Your someday is now." . "\n\n" .
                        "Start here: https://lnkd.in/gBnHYrjW" . "\n\n" .
                        "Skip what you hate." . "\n\n" .
                        "Build what you love.",

                    'Example 3:' . "\n\n" .
                        "The truth about building in public:" . "\n\n" .
                        "• Most people won't understand how you work." . "\n\n" .
                        "• They won't get why you're sharing." . "\n\n" .
                        "• They won't support your business dreams." . "\n\n" .
                        "But here's what successful people know:" . "\n\n" .
                        "• The opinions that matter are from the people doing the work." . "\n\n" .
                        "• The ones who are building their own paths." . "\n\n" .
                        "• The ones who understand the journey." . "\n\n" .
                        "Everyone else?" . "\n\n" .
                        "They're spectators." . "\n\n" .
                        "Commenting from the sidelines." . "\n\n" .
                        "Waiting to see how your story ends." . "\n\n" .
                        "Your job isn't to convince them." . "\n\n" .
                        "Your job is to keep building." . "\n\n" .
                        "Keep sharing." . "\n\n" .
                        "Keep growing." . "\n\n" .
                        "Because one day, they'll call your overnight success a \"10-year journey.\"" . "\n\n" .
                        "And you'll know the truth." . "\n\n" .
                        "Come start your journey in The Creator MBA." . "\n\n" .
                        "Over 6,000 entrepreneurs have joined to build businesses their way." . "\n\n" .
                        "Inside, you'll find:" . "\n\n" .
                        "→ 14 chapters of real strategies I've used" . "\n\n" .
                        "→ 111 lessons on actually doing the work" . "\n\n" .
                        "→ My LinkedIn revenue growth systems" . "\n\n" .
                        "→ My Notion setup for running everything" . "\n\n" .
                        "Stop waiting for permission from other people." . "\n\n" .
                        "Start building a new future." . "\n\n" .
                        "Watch here: https://lnkd.in/gBnHYrjW",

                    'Example 4:' . "\n\n" .
                        "The modern world wants us hooked." . "\n\n" .
                        "Notifications that never end." . "\n\n" .
                        "Meetings that steal our focus." . "\n\n" .
                        "Social media that drains our energy." . "\n\n" .
                        "Endless hustle culture that robs our peace." . "\n\n" .
                        "But here's what I've learned:" . "\n\n" .
                        "True wealth isn't a number in your bank account." . "\n\n" .
                        "It's having the freedom to:" . "\n\n" .
                        "• Think without interruption" . "\n\n" .
                        "• Rest without feeling guilty" . "\n\n" .
                        "• Move at your own pace" . "\n\n" .
                        "• Live life on your terms" . "\n\n" .
                        "The rat race promises success but delivers burnout." . "\n\n" .
                        "Breaking free means choosing a different path." . "\n\n" .
                        "Building something that serves you, not drains you." . "\n\n" .
                        "That's why I created The Creator MBA." . "\n\n" .
                        "It's helped 6,000+ people build businesses that prioritize freedom over hustle." . "\n\n" .
                        "Inside, you'll learn how to:" . "\n\n" .
                        "→ Build a brand that attracts opportunities" . "\n\n" .
                        "→ Launch products people want to buy" . "\n\n" .
                        "→ Generate consistent monthly revenue" . "\n\n" .
                        "→ Create automated systems to run it all" . "\n\n" .
                        "Ready to redefine your relationship with work?" . "\n\n" .
                        "Learn more: https://lnkd.in/ebaNJrie",

                    'Example 5:' . "\n\n" .
                        "We put our business heroes on pedestals." . "\n\n" .
                        "But here's the secret:" . "\n\n" .
                        "They're just regular people." . "\n\n" .
                        "Most people:" . "\n\n" .
                        "• Idolize success stories" . "\n\n" .
                        "• Believe they did it overnight" . "\n\n" .
                        "• Assume the person is special" . "\n\n" .
                        "Here's a reality check:" . "\n\n" .
                        "• Your heroes have doubts too" . "\n\n" .
                        "• Luck looks more like relentless practice" . "\n\n" .
                        "• \"Overnight success stories\" take decades" . "\n\n" .
                        "Successful people are just... people." . "\n\n" .
                        "Chock full of fears, flaws, and failures." . "\n\n" .
                        "Just like you." . "\n\n" .
                        "The only real difference?" . "\n\n" .
                        "They keep showing up." . "\n\n" .
                        "I've seen thousands transform their careers by embracing their skills, knowledge, and perspectives... not hiding them." . "\n\n" .
                        "Want to build genuine influence?" . "\n\n" .
                        "Want to learn how to share what you know so you can build for those people?" . "\n\n" .
                        "My 30-minute per day LinkedIn Operating System will show you how." . "\n\n" .
                        "No \"genius\" required." . "\n\n" .
                        "Just consistency and persistence." . "\n\n" .
                        "Watch it now: https://lnkd.in/eh9pVVuf" . "\n\n" .
                        "What imperfect step will you take today?",

                    'Example 6:' . "\n\n" .
                        "I'm not the smartest guy." . "\n\n" .
                        "I always got poor grades in school." . "\n\n" .
                        "And I certainly don't have a fancy degree." . "\n\n" .
                        "It turns out that most of those things are overrated." . "\n\n" .
                        "Especially in solopreneurship." . "\n\n" .
                        "What you need is persistence." . "\n\n" .
                        "A lot of folks forget this:" . "\n\n" .
                        "They stop learning." . "\n\n" .
                        "They skip the hard parts." . "\n\n" .
                        "They show up sometimes." . "\n\n" .
                        "But consistency beats talent when talent doesn't show up." . "\n\n" .
                        "And that's the secret." . "\n\n" .
                        "Show up." . "\n\n" .
                        "Stay curious." . "\n\n" .
                        "Keep learning." . "\n\n" .
                        "Do the work." . "\n\n" .
                        "I've built my entire business on this approach." . "\n\n" .
                        "What if I told you being \"average\" was your superpower?" . "\n\n" .
                        "That's exactly what I teach inside Creator MBA." . "\n\n" .
                        "5,000+ entrepreneurs have learned to build a lean, profitable, one-person business without:" . "\n\n" .
                        "• Genius-level intelligence" . "\n\n" .
                        "• Trust fund money" . "\n\n" .
                        "• 80-hour weeks" . "\n\n" .
                        "• Venture funding" . "\n\n" .
                        "They take consistent action. Every single day. On the right stuff." . "\n\n" .
                        "The outcome?" . "\n\n" .
                        "You just build a good business. That's it." . "\n\n" .
                        "Want to turn your consistency into revenue?" . "\n\n" .
                        "Start here: https://lnkd.in/gBnHYrjW",

                    'Example 7:' . "\n\n" .
                        "Angry people struggle in business." . "\n\n" .
                        "Positive people thrive." . "\n\n" .
                        "Why?" . "\n\n" .
                        "Because success isn't built on frustration, it's built on focus." . "\n\n" .
                        "Successful people understand how to channel their energy:" . "\n\n" .
                        "They don't waste time complaining." . "\n\n" .
                        "They invest time creating." . "\n\n" .
                        "The most successful people I know don't rage against reality." . "\n\n" .
                        "They either:" . "\n\n" .
                        "1. Work strategically to change it" . "\n\n" .
                        "2. Build something incredible within it" . "\n\n" .
                        "Your mindset isn't just personal... it's your competitive advantage." . "\n\n" .
                        "That's why I built The Creator MBA." . "\n\n" .
                        "It's a system to help you harness your energy and build with intention." . "\n\n" .
                        "6,000+ entrepreneurs have transformed their mindset to create thriving businesses." . "\n\n" .
                        "Instead of anger, they've chosen action." . "\n\n" .
                        "Instead of frustration, they've chosen focus." . "\n\n" .
                        "Your business deserves the right energy behind it." . "\n\n" .
                        "I recommend getting started with the 2.5-hour \"how to start from scratch\" mini-course inside: https://lnkd.in/gBnHYrjW",

                    'Example 8:' . "\n\n" .
                        "I choose simplicity over complexity." . "\n\n" .
                        "It improves my life dramatically." . "\n\n" .
                        "Most entrepreneurs believe they need to scale everything." . "\n\n" .
                        "More products." . "\n\n" .
                        "More customers." . "\n\n" .
                        "More employees." . "\n\n" .
                        "More capital." . "\n\n" .
                        "My one-person business works differently:" . "\n\n" .
                        "I work 3-4 hours a day." . "\n\n" .
                        "Serve one type of customer." . "\n\n" .
                        "Solve a few specific problems." . "\n\n" .
                        "And have almost no overhead." . "\n\n" .
                        "So I'm never:" . "\n\n" .
                        "- In meetings I hate" . "\n\n" .
                        "- Chasing investors" . "\n\n" .
                        "- Missing events with my wife" . "\n\n" .
                        "- Dealing with employee drama" . "\n\n" .
                        "- Working nights and weekends" . "\n\n" .
                        "I read books in the afternoon and travel whenever I want." . "\n\n" .
                        "A tiny business with 90% margins can create more personal wealth and freedom than a massive operation with 5% margins." . "\n\n" .
                        "Business isn't about impressing strangers at dinner parties." . "\n\n" .
                        "It's about designing the life you actually want to live." . "\n\n" .
                        "Here's my entire behind-the-scenes playbook (There's a 2.5-hour \"get started\" course inside) that will teach you how to build this." . "\n\n" .
                        "Start here: https://lnkd.in/gBnHYrjW" . "\n\n" .
                        "What's the smallest business that would give you the life you want?" . "\n\n" .
                        "Build that." . "\n\n" .
                        "Then ruthlessly eliminate everything else.",

                    'Example 9:' . "\n\n" .
                        "Don't build a business." . "\n\n" .
                        "Build a lifestyle that generates income." . "\n\n" .
                        "Most people work themselves to death chasing the wrong goal." . "\n\n" .
                        "They build empires that trap them." . "\n\n" .
                        "They create systems that own them." . "\n\n" .
                        "They pursue growth that drains them." . "\n\n" .
                        "How backward is that?" . "\n\n" .
                        "The smartest entrepreneurs do it differently:" . "\n\n" .
                        "They started with the life they wanted." . "\n\n" .
                        "Then designed work to support it." . "\n\n" .
                        "Never the other way around." . "\n\n" .
                        "This isn't about being lazy." . "\n\n" .
                        "It's about being intentional." . "\n\n" .
                        "When your business serves your priorities instead of consuming them, everything changes." . "\n\n" .
                        "Your boundaries get stronger." . "\n\n" .
                        "Your decisions get clearer." . "\n\n" .
                        "Your work gets better." . "\n\n" .
                        "My LinkedIn OS shows you how to create leverage, automate growth, and design freedom into your work using LinkedIn." . "\n\n" .
                        "Join 35,000 students who've transformed their approach." . "\n\n" .
                        "Start here: https://lnkd.in/eh9pVVuf" . "\n\n" .
                        "Remember — when you build your business this way, people won't understand it." . "\n\n" .
                        "They'll call you unambitious." . "\n\n" .
                        "Let them." . "\n\n" .
                        "Freedom feels better than their approval anyway.",

                    'Example 10:' . "\n\n" .
                        "Most people wake up at 65 and wonder where life went." . "\n\n" .
                        "They followed the script:" . "\n\n" .
                        "• Good grades." . "\n\n" .
                        "• Safe job." . "\n\n" .
                        "• Steady paycheck." . "\n\n" .
                        "Then, one day, they realize:" . "\n\n" .
                        "Time is running out." . "\n\n" .
                        "Dreams are fading." . "\n\n" .
                        "Confucius said it perfectly:" . "\n\n" .
                        "“We have two lives, and the second begins when we realize we only have one.”" . "\n\n" .
                        "That wake-up call?" . "\n\n" .
                        "It doesn't have to be at 65." . "\n\n" .
                        "It can happen right now." . "\n\n" .
                        "Stop living someone else's life." . "\n\n" .
                        "Stop chasing someone else's dreams." . "\n\n" .
                        "Stop following someone else's path." . "\n\n" .
                        "Your second life is waiting." . "\n\n" .
                        "Making the leap is terrifying." . "\n\n" .
                        "Building something new is hard." . "\n\n" .
                        "That's exactly why I built The Creator MBA." . "\n\n" .
                        "It's everything I wish I had when I started my second life:" . "\n\n" .
                        "→ Real strategies (no fluff)" . "\n\n" .
                        "→ Proven systems" . "\n\n" .
                        "→ Clear roadmap" . "\n\n" .
                        "5,000+ entrepreneurs have already started their second chapter." . "\n\n" .
                        "Ready for yours?" . "\n\n" .
                        "Learn more: https://lnkd.in/gBnHYrjW"
                ],
            ],
            'Technology' => [
                'Bernard Marr' => [
                    'Example 1:' . "\n\n" .
                        "Are Open Source AI Agents the Future?\n\n" .
                        "🔓 Will AI agents be dominated by proprietary systems like ChatGPT or open-source alternatives? Manus AI represents an interesting middle ground—built with some open components including Alibaba's Qwen while maintaining proprietary elements. \n\n" .
                        "Proprietary systems benefit from massive resources and controlled development (like iPhone), while open-source enables global innovation, transparency, and customization (like Android).\n\n" .
                        "The ideal future involves a balanced ecosystem where proprietary systems push boundaries while open-source provides accessible options and specialized solutions!\n\n" .
                        "https://lnkd.in/dnTA5N2t\n\n" .
                        "#OpenSourceAI #AIagents #TechInnovation #ManusAI #AIecosystem #FutureTech #TechStrategy #BernardMarr",

                    'Example 2:' . "\n\n" .
                        "AI Therapists Are Here: 14 Groundbreaking Mental Health Tools You Need To Know\n\n" .
                        "New #GenerativeAI #mentalhealth #apps are revolutionizing #therapy by offering anonymous support through science-backed methods including #CBT and #mindfulness techniques.\n\n" .
                        "From #Headspace to #Woebot, these innovative tools provide 24/7 accessibility to #mental #wellness services, with studies showing measurable results in as little as two weeks.",

                    'Example 3:' . "\n\n" .
                        "Exciting news — AI Strategy: Unleash the Power of Artificial Intelligence in Your Business officially lands in Canada and the US tomorrow! 🇨🇦🇺🇸\n\n" .
                        "Whether you're just getting started or scaling your AI efforts, this book gives you a practical roadmap to harness AI effectively across any organisation.\n\n" .
                        "Covering everything from data and ethics to change management and real-world examples — this is your toolkit to lead in the age of AI.\n\n" .
                        "Buy or pre-order now to get your copy as soon as it drops 👉 https://lnkd.in/euJMryvd\n\n" .
                        "#AIinBusiness #AILeadership #USBookLaunch #CanadaBookLaunch #DigitalStrategy #BernardMarr #ArtificialIntelligence",

                    'Example 4:' . "\n\n" .
                        "📝 Fix Your To-Do List! Traditional to-do lists are hurting your productivity. Learn the system used by top executives: categorize tasks into 5-minute tasks, focus work, and strategic projects. 💼\n\n" .
                        "📌 Key Topics:\n\n" .
                        "* Why traditional lists don’t work\n" .
                        "* A new system for task management\n" .
                        "* Tips to maximize productivity\n\n" .
                        "👉 Try this hack today and see the difference!\n\n" .
                        "#ProductivityHacks #TimeManagement #BernardMarr #SuccessTips #WorkSmarter",

                    'Example 5:' . "\n\n" .
                        "Leadership At Warp Speed: How To Drive Success In The Era Of Agentic AI\n\n" .
                        "In the age of intelligent agents, leadership must evolve. Discover the critical shifts leaders need to make to stay ahead and drive success in a rapidly changing AI-powered world.\n\n" .
                        "Read more 👉 https://lnkd.in/eeXm-smk\n\n" .
                        "#Leadership #AgenticAI #AITransformation #BernardMarr",

                    'Example 6:' . "\n\n" .
                        "Why Critical Thinking Is Your Best Weapon Against The Coming Deepfake Tsunami\n\n" .
                        "#Deepfake technology is evolving faster than detection tools can keep pace, creating unprecedented #business #risks, including #fraud and #disinformation. \n\n" .
                        "This expert analysis reveals why #criticalthinking #skills and employee training are more crucial than technological solutions for protecting your organization in our increasingly #synthetic digital landscape.",

                    'Example 7:' . "\n\n" .
                        "5 Mistakes Companies Will Make This Year With Cybersecurity\n\n" .
                        "#Businesses are facing unprecedented #cybersecurity threats from #AI-powered attacks, unprepared employees, and insider #vulnerabilities that could devastate their bottom line.\n\n" .
                        "Here are the five critical cybersecurity mistakes companies are making in 2025 and provides actionable #strategies for building #resilience against increasingly sophisticated #digital #threats.",

                    'Example 8:' . "\n\n" .
                        "The future of business is AI-driven — and my new book, AI Strategy: Unleash the Power of Artificial Intelligence in Your Business, is here to help you lead with confidence.\n\n" .
                        "Already available in the UK, it officially launches in the US and Canada on 29 April!\n\n" .
                        "Inside you’ll find a complete guide to AI implementation: practical frameworks, ethical insights, and examples from companies making AI work across industries.\n\n" .
                        "Buy or pre-order now 👉 https://lnkd.in/euJMryvd\n\n" .
                        "#AIPlaybook #AIBookLaunch #DigitalTransformation #LeadershipTools #AIForExecutives #BernardMarr #BusinessInnovation",

                    'Example 9:' . "\n\n" .
                        "What's The Difference Between AI Agents And Agentic AI?\n\n" .
                        "🤖 Confused about AI agents and agentic AI? This video clears it up! AI agents are specific applications solving multi-step problems independently, while agentic AI is the entire research field making these agents possible. Think of AI agents as specific medicines and agentic AI as pharmaceutical science.\n\n" .
                        "Today's AI agents aren't AGI yet—they're specialized tools for specific purposes. Understanding these developments is essential as increasingly sophisticated agents emerge to automate various tasks. 🔍💡\n\n" .
                        "https://lnkd.in/e3-wAQeP\n\n" .
                        "#AIAgents #AgenticAI #ArtificialIntelligence #FutureTech #BusinessTechnology #AGI #TechTrends",

                    'Example 10:' . "\n\n" .
                        "📣 Join me later today for my latest #livestream - 'Agentic AI: How Zoom Is Transforming Work Beyond Video Conferencing' 🤖💡\n\n" .
                        "> 12 Noon UK | ET 7AM | PT 4AM | CET 1PM | SGT 7PM\n\n" .
                        "LinkedIn > https://lnkd.in/gsq28F2\n" .
                        "Twitter > https://lnkd.in/ehrXiUW\n" .
                        "Facebook > https://lnkd.in/e_9hcfph\n" .
                        "YouTube > https://lnkd.in/dMhGX2q\n\n" .
                        "Discover how #AI is evolving from simple #automation to autonomous systems that can reason, plan, and execute tasks on your behalf! 🚀\n\n" .
                        "I'm joined by Xuedong D. Huang, CTO of Zoom, to explore how #Zoom is pioneering agentic AI to transform meetings into milestones 🎯, the power of specialized small language models 🧠, how #AICompanion acts as your personal chief of staff 💼, and what the #future of #workplace #collaboration will look like when we all have #AIagents working for us 24/7 ⏰.",

                    'Example 11:' . "\n\n" .
                        "⚡ Supercharge your productivity with AI agents! These systems excel at research, data analysis, and routine processes—not replacing you but amplifying your capabilities. Treat them as apprentices handling time-consuming groundwork while you focus on strategy and creativity.\n\n" .
                        "Identify your productivity bottlenecks, assign specific yet comprehensive tasks, verify and refine their output, develop complementary human skills like creativity and ethical judgment, establish feedback loops, and aim for augmentation rather than automation to achieve more with less effort!\n\n" .
                        "https://lnkd.in/eE68CDmt\n\n" .
                        "#AIproductivity #DigitalAssistants #WorkplaceAI #AIagents #ProductivityHacks #FutureOfWork #AIcollaboration #BernardMarr"
                ],
                'Elena Verna' => [
                    'Example 1:' . "\n" .
                        "Most teams treat analytics like a fire extinguisher - only grabbing it when something's on fire. It goes something like this: A metric drops. No one knows why. Panic sets in. Everyone drops what they’re doing. Slack explodes. Dashboards get torn apart. Fingers point. Hotfixes fly.\n\n" .
                        "🚨 That’s *defensive analysis*: reactive, chaotic, and purely damage control. It will help protect existing revenue - but it won’t help you grow.\n\n" .
                        "To actually move the needle, you need *offensive analysis*.\n\n" .
                        "Offensive analysis doesn't wait for fires. It finds what’s already working - and pours fuel on it. It asks:\n" .
                        "- Which segments are crushing it - and what’s driving that?\n" .
                        "- Who's breezing through key steps - and what sets them apart?\n" .
                        "- What features are beloved by loyal users - but ignored by the rest?\n\n" .
                        "This is where you find the outsized wins and step function changes in your growth rates.\n\n" .
                        "Want to bend the growth curve up and to the right? Build your analytics strategy around offensive analytics and leave just ~20% for firefighting.\n\n" .
                        "Check out examples of the offensive analytics from companies like Uber, Pinterest, and Slack + suggestions on how to implement it on your team in my newsletter, co-written with Tom Laufer! \n" .
                        "https://lnkd.in/eJixFE5h\n\n" .
                        "#growth",

                    'Example 2:' . "\n" .
                        "We’re rapidly heading toward a future where many of our traditional full-time jobs may no longer exist. We all know it’s happening - we see the cliff ahead, we talk about it, we’re even afraid of it - yet we keep accelerating toward it.\n\n" .
                        "The institution (think companies, investors, and the market) is fueling that momentum, pressuring us to make AI smarter, faster, and cheaper. But instead of pushing back for the sake of our own well-being and survival, we’re very much playing along.\n\n" .
                        "So now comes the time where you have to start thinking about your future career. And consider whether traditional full-time role is still the best bet.\n\n" .
                        "Most people still equate full-time jobs with stability, which makes sense - they’ve been the default for so long. But that stability? It’s an illusion. If recent waves of layoffs did not yet shatter this belief, it will as more and more companies will experience product-market fit collapse, and your full-time job could disappear before you even see it coming.\n\n" .
                        "And it will get even worse as the new open roles will begin to dry up - just look at Shopify: they’ve already made it a requirement that any new role must be justified by proving AI can’t do it first.\n\n" .
                        "So when people say solopreneurship is risky, I have to disagree.\n\n" .
                        "Building a diversified business around your skills is actually the most secure way to work. You own your skills, your reputation, and your distribution. That, to me, is security.\n\n" .
                        "Because the ultimate career flex isn’t chasing titles, building someone else’s dream, or flying first class to off-sites. It’s having career optionality - being in a position where full-time roles are just one of many ways to work, not the only way, and definitely not a requirement.\n\n" .
                        "Everyone, and I mean *everyone*, should be putting their 'ceo' hat on and begin evolving their career with optionality in mind: a system that gives you leverage, protects your independence, and keeps your skills relevant, no matter how fast the world changes.\n\n" .
                        "More in my today's newsletter: https://lnkd.in/eV3sRhxG\n\n" .
                        "#growth",

                    'Example 3:' . "\n" .
                        "Eight of my best growth hacks:\n" .
                        "1. Rebrand churn as “intentional growth pruning.” We didn’t lose 30% of users - they just weren’t aligned with our vision.\n\n" .
                        "2. Use the highest-converting CTAs: “Click to save a starving child.” It’s not manipulative if it works.\n\n" .
                        "3. Triple user growth overnight by making paywalls optional.\n\n" .
                        "4. Run A/B tests where both variants are control. 5% of the time you'll hit stat sig lift - the easiest win EVAH.\n\n" .
                        "5. Add a fake loading spinner to make your product feel more powerful. The longer it loads, the more enterprise-y it feels, the more you can charge.\n\n" .
                        "6. Rename your experiment “strategic initiative” to unlock more budget. Same test, $200K more funding.\n\n" .
                        "7. Hike prices, enjoy the revenue boost & praise, then leave before year-over-year declining growth hits.\n\n" .
                        "8. Set quarterly OKRs no one understands, then pivot mid-cycle and call it agile. Gaslight anyone who questions you - they are NOT agile.\n\n" .
                        "You're welcome.",

                    'Example 4:' . "\n" .
                        "SEO - dead. Paid Marketing - dead. Engineering - dead too. (kidding!)\n" .
                        "But you know what’s never dead? Churn. Churn eats at your business, stalling your growth. Here are my 10 go-to churn reduction tactics I apply at every business.\n\n" .
                        "1. Drive paid feature utilization. If users aren’t using what they paid for, they won’t stick around.\n\n" .
                        "2. Don’t wait till churn happens:\n" .
                        "   -> Get activation right. This means nailing setup, hitting the “aha!” moment quickly, and building habit loops.\n" .
                        "   -> Ensure healthy ongoing engagement from paid users (your paid WAU).\n\n" .
                        "3. Make reactivating auto-renew one click across every surface.\n\n" .
                        "4. Be aggressive with payment failure comms.\n\n" .
                        "5. When users cancel auto-renew, show them what they’ve used and what they’ll lose.\n\n" .
                        "6. Score users for churn risk. Offer discounts or comped time for “high-risk.”\n\n" .
                        "7. Offer a pause option.\n\n" .
                        "8. Make your pricing and packaging flexible.\n\n" .
                        "9. Move tenured monthly customers to annual subscriptions.\n\n" .
                        "10. Human touch for high-value accounts.\n\n" .
                        "This + My most tried and true churn benchmarks in my latest newsletter: https://lnkd.in/e3_aEWzZ\n\n" .
                        "This week's newsletter is sponsored by Churnkey - they help you reduce your involuntary churn. Do give them a try!\n\n" .
                        "#growth",

                    'Example 5:' . "\n" .
                        "Company blogs are no longer worth the investment. 🫨 Some of the biggest tech SaaS blogs - HubSpot, Salesforce, Atlassian, Gong, Intercom - are seeing traffic declines or complete growth stagnation.\n\n" .
                        "But here’s a twist - my newsletter gets ~300K visits per month vs. Salesforce blog’s ~200K.\n\n" .
                        "Why?\n" .
                        "1. Google made Search worse to squeeze more ad revenue.\n" .
                        "2. AI is becoming the new interface for content consumption.\n" .
                        "3. Trust in companies is at an all-time low, fueling the creator economy.\n\n" .
                        "What should companies do?\n" .
                        "-> Invest in creators\n" .
                        "-> Double down on docs & case studies\n" .
                        "-> Diversify into YouTube, Reddit, TikTok\n\n" .
                        "🔴 Ask yourself: is your blog a growth engine or just a checkbox?\n" .
                        "Read full post here: https://lnkd.in/gtXsCCSu\n" .
                        "Huge thanks to Semrush!\n\n" .
                        "#growth",

                    'Example 6:' . "\n" .
                        "I’m always asked about the best online courses for Growth folks. My go-to is Reforge, obvi.\n\n" .
                        "1️⃣ Growth Foundations (Chen & Balfour)\n" .
                        "2️⃣ Experimentation (Mosavat & me)\n" .
                        "3️⃣ Retention & Engagement (Winters, Clowes, Warren)\n" .
                        "4️⃣ Product-Led Growth (Watkins & me)\n" .
                        "5️⃣ Monetization + Pricing Strategy (Hockenmaier & Campbell)\n" .
                        "6️⃣ (Advanced) Growth Strategy (Kwok & Winters)\n\n" .
                        "These are built by insanely smart operators and packed with real examples. #reforge4life\n\n" .
                        "#growth #reforge4life",

                    'Example 7:' . "\n" .
                        "\"We need to build an amazing brand,\" says every company. But look at Coke’s \$4B spend vs. Notion/Figma/Loom/Miro’s product-driven loops:\n\n" .
                        "→ “Powered by Shopify”\n" .
                        "→ “Made with Notion”\n" .
                        "→ “Sent via Superhuman”\n" .
                        "→ “Chat ⚡ by Drift”\n" .
                        "→ “Powered by SurveyMonkey”\n\n" .
                        "These low-effort, high-impact branding loops cost \$0. Read how to embed them: https://lnkd.in/eyEiqTPj\n" .
                        "Thanks Brand24!\n\n" .
                        "#product #brand",

                    'Example 8:' . "\n" .
                        "Many B2B businesses have self-serve users but zero pipeline. End users don’t care about your enterprise features.\n\n" .
                        "Here’s how Miro, Unity & Clay do outreach to self-serve users and WIN. Text plays, data needs, benchmarks & pitfalls here:\n" .
                        "https://lnkd.in/eUg9Zvvq\n\n" .
                        "Thanks Clay for sponsoring! Check out their new scheduling feature.\n\n" .
                        "#growth",

                    'Example 9:' . "\n" .
                        "Most PLG motions fail not because of product but because of data.\n\n" .
                        "You need real-time tracking of:\n" .
                        "✅ Demographics\n" .
                        "✅ Acquisition\n" .
                        "✅ Activation\n" .
                        "✅ Monetization\n" .
                        "✅ Engagement\n\n" .
                        "And the right tools:\n" .
                        "🛠 CDP (Segment, Amplitude)\n" .
                        "📊 Behavioral analytics (Amplitude, Mixpanel)\n" .
                        "📂 CRM (Salesforce or Clarify)\n\n" .
                        "Get your stack right, and your PLG motion will thrive: https://lnkd.in/enzWnBah\n\n" .
                        "#growth #plg",

                    'Example 10:' . "\n" .
                        "What would make you PROUD if you retired tomorrow?\n\n" .
                        "New season of Growthmates podcast — “In the Company of Women” with Elena Verna.\n\n" .
                        "Highlights:\n" .
                        "✦ Superpower Zone: Love + expertise + demand.\n" .
                        "✦ Wake-Up call: health scare shifted priorities.\n" .
                        "✦ Saying NO builds respect.\n\n" .
                        "Listen on YouTube/Spotify/Apple: https://lnkd.in/e_WM7HVP\n\n" .
                        "Special thanks to Amplitude 💜\n\n" .
                        "#growth #career",
                ],
                'Harry Stebbings' => [
                    'Example 1:' . "\n" .
                        "Pre-seed investments are very simple for me:\n\n" .
                        "1. Is the founder truly world class?\n\n" .
                        "2. Are they operating in a market that is directionally correct? TBD so many things but right zone of play?\n\n" .
                        "3. Is the deal zone palatable. $10M on $100M we do not do. Is there a deal to be done?\n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 2:' . "\n" .
                        "Life is defined by few very special moments with the people you love. Do not miss them. \n\n" .
                        "As always, Mama Stebbings and me did our Sunday marathon and finished with the best hug of all. 👇 \n\n" .
                        "Don’t miss these moments. Call the ones you love, see them, tell them what they mean to you. \n\n" .
                        "You never have enough time with the people who mean the most to you. Savour every second. 🚀 \n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 3:' . "\n" .
                        "Project Europe will change the trajectory of European innovation. \n\n" .
                        "We have the talent. \n\n" .
                        "We have the ambition. \n\n" .
                        "We have the chip on our shoulder. \n\n" .
                        "It’s time to go. \n\n" .
                        "Kitty Mayo on the ground in Berlin and Hamburg. LFG 🔥\n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 4:' . "\n" .
                        "I have never made money in markets with many competitors in limited TAMS (Total Addressable Market Size). \n\n" .
                        "I have often made money in markets with many competitors and massive TAMS. \n\n" .
                        "Market size / competitive landscape ratio matters.\n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 5:' . "\n" .
                        "Series A and B has never been more competitive. \n\n" .
                        "Just got off a call with a Series B investor who did 100 customer reference calls just to be able to package them to the founders to encourage them to take a first meeting. \n\n" .
                        "Company was at $5M ARR.\n\n" .
                        "Another level competition.\n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 6:' . "\n" .
                        "Controversial take: \n\n" .
                        "Don’t ever take an investor “that requires a lead for them to invest”. \n\n" .
                        "If they have conviction, they invest. \n\n" .
                        "Don’t have time for passengers or tourists who can build their own conviction. \n\n" .
                        "Back yourself, say no to passengers. \n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 7:' . "\n" .
                        "Three things that make me very excited when I hear founders say them in a pitch: \n\n" .
                        "1. This is my life’s work. There is no next company. \n\n" .
                        "Truly generational defining companies are the life mission of the founder. Unwavering focus and obsession is everything. \n\n" .
                        "2. Price wise we will let the market decide but we won’t rush the process.\n\n" .
                        "Your job is to get the best deal but it is also to get the best partnership. This encapsulates both. \n\n" .
                        "When we wrote a $7M check into Fyxer AI, Richard Hollingsworth mastered the process of getting the best deal without sacrificing relationship building. \n\n" .
                        "3. “Why would I ever sell a secondary? I want to invest more with every round.” \n\n" .
                        "Best seen by Fabien Pinckaers. No greater sign of commitment and unwavering belief in what you are doing. \n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 8:' . "\n" .
                        "My biggest advice to someone young in venture: \n\n" .
                        "Choose the firm not with the biggest AUM or brand but where your rate of learning is highest and fastest. \n\n" .
                        "Where you are tied to a partner you respect and has seen it all. \n\n" .
                        "Remote won’t work. \n\n" .
                        "Venture is an apprentice business.\n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 9:' . "\n" .
                        "“All the best startups I have invested in are working 7 days per week, 12 hours per day. In the office.\n\n" .
                        "Everyone is working 7/12 and it’s a vibe.” \n\n" .
                        "20VC episode today with Rory O'Driscoll and Jason M. Lemkin. 🔥 👇 \n\n" .
                        "#Founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",

                    'Example 10:' . "\n" .
                        "I have been fortunate to invest in 9 unicorns over the last 8 years. \n\n" .
                        "There are five traits that all the best have and why they led to 20VC leading the seed round for Delos. \n\n" .
                        "1.⁠ Unwavering Discipline…\n\n" .
                        "2.⁠ Speed of execution…\n\n" .
                        "3.⁠ Capital efficiency…\n\n" .
                        "4.⁠ Tightness of team bond…\n\n" .
                        "5.⁠ Market Timing & Momentum…\n\n" .
                        "We cannot wait to work with you Thibaut de la Grand'rive… LFG! \n\n" .
                        "#founder #funding #business #investing #vc #venturecapital #entrepreneur #startup",
                ],
                'Kieran Flanagan' => [
                    'Example 1:' . "\n\n" .
                        "Here\'s how to create the marketing plan of the future with o3 and a single prompt\n\n" .
                        "o3 is an incredible model. It feels like a big step up as a strategic thought partner.\n\n" .
                        "Here\'s a prompt that creates a marketing plan for a B2B SaaS company created with AI in mind.\n\n" .
                        "It categorizes all marketing work into three buckets.\n\n" .
                        "- What should AI fully automate in the future\n" .
                        "- What should AI do and humans make better\n" .
                        "- What tasks will be human only?\n\n" .
                        "The prompt will work with all models, but o3 produces much better results.\n\n" .
                        "\"From first principles, develop a detailed, insanely well-researched and well-reasoned AI-powered marketing strategy for a B2B SaaS company to thrive over the next 5 years. Categorize all marketing tasks and workflows into three clear buckets:\n\n" .
                        "1. All Done by AI – fully automated by AI in the near future\n" .
                        "2. AI + Human – AI creates the first draft or version, and humans refine the last 20%\n" .
                        "3. Human Only – uniquely human tasks that AI will not be able to handle\n\n" .
                        "The strategy should cover the entire marketing funnel—from audience research and content creation to campaign execution, lead nurturing, and performance optimization.\n\n" .
                        "For each task or category, include:\n\n" .
                        "- What it is and why it fits that bucket\n" .
                        "- What tools or technologies are (or will be) best for it\n" .
                        "- How to implement or transition to this AI-driven model\n" .
                        "- What risks or blind spots to watch for\n" .
                        "- How success should be measured\n\n" .
                        "The goal is to help a modern marketing team understand where to double down on AI, where to collaborate with it, and where human creativity and intuition will continue to lead.\"\n\n" .
                        "Enjoy!",

                    'Example 2:' . "\n\n" .
                        "Here\'s a quick and powerful tactic - 'The Prompt Chain' - to instantly level up your AI prompting and get expert-level results in seconds\n\n" .
                        "You start with a simple prompt structure:\n\n" .
                        "- Goal: Clearly state your main goal with specifics.\n" .
                        "- Return Format: Specify the exact output structure and required details.\n" .
                        "- Warnings: Highlight potential pitfalls or accuracy considerations.\n" .
                        "- Context: Provide relevant background, preferences, and any relevant docs etc.\n\n" .
                        "Ask the ChatGPT to create a prompt in the above structure.\n\n" .
                        "Then, go to Gemini 2.5 PRO, paste in the prompt, and ask how it would improve the prompt to improve the output.\n\n" .
                        "Then, go to Claude 3.7/3.5 and ask the same question.\n\n" .
                        "And round you go until you end up with a prompt you feel good about, and edit to your taste.\n\n" .
                        "The result is an incredibly powerful way to prompt! Try it now.",

                    'Example 3:' . "\n\n" .
                        "Google just launched Veo 2 in Gemini, their latest text-to-video AI.\n\n" .
                        "We've got incredible creative models for text and images. Is Veo 2 the unlock for video?\n\n" .
                        "No :)\n\n" .
                        "We're still in the early \"DALL-E phase\" of video models—fun experiments but mediocre results:\n\n" .
                        "- Limited creativity\n" .
                        "- Struggles with detailed instructions\n" .
                        "- Text in videos is still consistently misspelled\n\n" .
                        "Compare that to ChatGPT's recent image model which solved all those problems for text-to-image generation—and reportedly doubled their user base to 1 billion in weeks.\n\n" .
                        "Creative AI tools will onboard the masses. We've seen it with text & images.\n" .
                        "Veo 2 is an exciting step, but there's still a long way to go before text-to-video has its breakthrough moment.\n\n" .
                        "Here's my attempt at a fun ad for my newsletter—even the AI knows where to get the latest tips, tricks, and AI knowledge :)",

                    'Example 4:' . "\n\n" .
                        "Every marketer has their own McKinsey consultant—if they know how to prompt Deep Research effectively.\n\n" .
                        "If you prompt Deep Research like an intern, don't be surprised when it gives you intern-level insights.\n\n" .
                        "But if you prompt it like a strategist?\n\n" .
                        "It can surface tools, teardown trends, and write marketing roadmaps like a seasoned operator.\n\n" .
                        "It will become one of your best colleagues.\n\n" .
                        "Here's a simple Deep Research prompt template to turn prompts into research reports so you can work faster.\n\n" .
                        "The breakdown is simple:\n" .
                        "1. Content\n" .
                        "2. Assignment\n" .
                        "3. Objectives & Key Metrics\n" .
                        "4. Scope & Priorities\n" .
                        "5. Define Output\n\n" .
                        "As a marketer, you can use this for anything relevant to your current goals.\n\n" .
                        "→ \"What AI tools will help grow our audience this quarter?\"\n" .
                        "→ \"Which vendors show proof—not just hype—they can grow traffic for brands like ours?\"\n\n" .
                        "The prompt is built by a marketer—inspired by strategy consultants :)",

                    'Example 5:' . "\n\n" .
                        "Here are 5 incredibly powerful ways marketers can use ChatGPT today\n\n" .
                        "1. Project Management Assistant\n\n" .
                        "- Create a dedicated ChatGPT project for each marketing goal\n" .
                        "- Upload all relevant documents, meeting notes, and context\n" .
                        "- Set clear instructions for the assistant to: (provide evidence-based recommendations, challenge your thinking, surface potential blind spots)\n" .
                        "- Use it for everything related to your goal—progress updates, identify risks, opportunities and more.\n\n" .
                        "2. Automated Update Generation\n\n" .
                        "Generate weekly, monthly, and executive updates automatically\n\n" .
                        "Steps:\n" .
                        "a. Collect past update examples\n" .
                        "b. Ask ChatGPT to create update templates\n" .
                        "c. Create a custom GPT assistant with these templates\n" .
                        "d. Use the assistant to quickly draft updates whenever needed\n\n" .
                        "3. Customer Insight Generator\n\n" .
                        "Replace traditional market research with ChatGPT-powered insights\n\n" .
                        "Two key strategies:\n\n" .
                        "a. Persona Feedback\n" .
                        "- Describe your product and value proposition\n" .
                        "- Have ChatGPT describe your ideal buyer personas\n" .
                        "- Have ChatGPT roleplay these personas\n" .
                        "- Get instant, targeted feedback on messaging, positioning and upcoming marketing campaigns\n\n" .
                        "b. Personalized Messaging\n" .
                        "- Generate tailored marketing copy\n" .
                        "- Bonus: Integrate customer call transcripts for better precision\n\n" .
                        "4. Marketing Task Templating\n\n" .
                        "Never start from scratch again\n\n" .
                        "How to create templates:\n\n" .
                        "a. Gather your best past work (positioning docs, campaign briefs)\n" .
                        "b. Use ChatGPT to convert these into reusable templates\n" .
                        "c. Standardize your workflow\n" .
                        "d. Produce consistently high-quality work with minimal effort\n\n" .
                        "5. ChatGPT Deep Research\n\n" .
                        "This is one of the best AI tools there is, but the prompting really matters. I'm giving my newsletter subscribers two of my best Deep Research prompts next week. Signup to not miss out. Plus, I'm giving an incredible bonus tip on how I create my own prompt engineers to do all this for me.\n\n" .
                        "ChatGPT keeps getting more powerful. Last night it just got memory. Will cover in a future update :)",

                    'Example 6:' . "\n\n" .
                        "Anthropic just released fascinating research that flips our understanding of how AI models \"think.\"\n\n" .
                        "Here's the breakdown:\n\n" .
                        "The Surprising Insight: Chain of thought (CoT)—where AI models show their reasoning step-by-step—might not reflect actual \"thinking.\" Instead, models could just be telling us what we expect to hear.\n\n" .
                        "When Claude 3.7 Sonnet explains its reasoning, those explanations match its actual internal processes only 25% of the time. DeepSeek R1 does marginally better at 39%.\n\n" .
                        "Why This Matters: We rely on CoT to trust AI decisions, especially in complex areas like math, logic, or coding. If models aren’t genuinely reasoning this way, we might incorrectly believe they're safe or transparent.\n\n" .
                        "How Anthropic Figured This Out: Anthropic cleverly tested models by planting hints in the prompt. A faithful model would say, “Hey, you gave me a hint, and I used it!” Instead, models used the hints secretly, never mentioning them—even when hints were wrong!\n\n" .
                        "The Counterintuitive Finding: Interestingly, when models lie, their explanations get wordier and more complicated—kind of like humans spinning a tall tale. This could be a subtle clue to spotting dishonesty. It works on humans and works on AI.\n\n" .
                        "Practical Takeaways:\n\n" .
                        "- CoT might not reliably show actual AI reasoning.\n" .
                        "- Models mimic human explanations because that's what they're trained on—not because they're genuinely reasoning step-by-step.\n\n" .
                        "What It Means for Using AI Assistants Today:\n\n" .
                        "- Take AI explanations with a grain of salt—trust, but verify, especially for important decisions.\n" .
                        "- Be cautious about relying solely on AI reasoning for critical tasks; always cross-check or validate externally.\n" .
                        "- Question explanations that seem overly complex or conveniently reassuring.",

                    'Example 7:' . "\n\n" .
                        "A leaked Shopify memo shows Tobi's (CEO) bet on going AI-first. Here's the two critical moves he's making:\n\n" .
                        "All of these are great, but 4 and 5 are critical\n\n" .
                        "4. Learning is self-directed, but share what you learned:\n\n" .
                        "Employees need to take ownership of their AI knowledge. You can't rely on others to teach you. You have to do the work.\n\n" .
                        "But, share what you learn. The best way to accelerate adoption in your company is through inspiration.\n\n" .
                        "Encourage teams to share their AI use cases and workflows with others.\n\n" .
                        "5. Before asking for more headcount and resources, leaders should ask if AI can do this.\n\n" .
                        "Constraints are everything.\n\n" .
                        "We need to think AI first. Constraints will cause everyone to adopt that mentality and look for ways AI can add productivity and efficiency across everything we do.",

                    'Example 8:' . "\n\n" .
                        "AI won't constrain creativity—it'll set it free. And that's great for marketing.\n\n" .
                        "Last week, we got countless jaw-dropping AI releases—Google's Gemini 2.5, Alibaba's Qwen2.5-Omni-7B, and DeepSeek's V3.\n\n" .
                        "But guess what actually went viral?\n\n" .
                        "A quirky, playful trend sparked by OpenAI's new image model—using the Studio Ghibli style to create images.\n\n" .
                        "It turns out people want to let their creative side out.\n\n" .
                        "AI makes it easier to 'vibe creatively.' Jam with AI on ideas until you find one that resonates.​\n\n" .
                        "This should elevate marketing—making it sharper, smarter, and more fun.\n\n" .
                        "To thrive in an AI-first world, marketers can't afford to be 'good enough.' You must operate at the extremes—deeply engineering-driven on one end, wildly creative on the other.\n\n" .
                        "Think like an engineer, integrating AI across your teams.\n\n" .
                        "Develop wildly creative ideas to stand out. When everyone can turn ideas into creative content in seconds, the bar to stand out is much higher.\n\n" .
                        "The riskiest move for marketers in an AI world is being 'average.'",

                    'Example 9:' . "\n\n" .
                        "Project AI Assistants are the secret weapon to 10x your productivity. They're one of my favorite ways to use AI.\n\n" .
                        "Here's how to build one in minutes\n\n" .
                        "You can use ChatGPT Projects, Claude Projects, or Gemini Gems for your Project AI assistant.\n\n" .
                        "You create a separate project assistant to manage each major outcome you're accountable for, e.g., grow demand by 30%, double weekly active users, use AI to increase closed-won deals by 50% etc.\n\n" .
                        "For each Project AI assistant:\n\n" .
                        "1. Give it all the context: People don't understand how amazing AI is at holding all the context for you. Give it:\n" .
                        "- All the project's strategic documents.\n" .
                        "- All the project's meeting transcripts.\n" .
                        "- Bonus: use a meeting app like 'Fellow' to attend meetings on your behalf and grab the meeting notes; now your assistant has context across all meetings, even if you're not in them.\n" .
                        "- Loom transcripts. Have the team send updates in Looms; it's a huge unlock.\n" .
                        "- External Deep Research: pairing external research with internal is powerful.\n\n" .
                        "2. Instructions: Provide your project assistant with clear instructions on how to work with you. Below is just a tiny sample from mine:\n" .
                        "a. Be clear and concise: Get to the point, but add context where needed. Prioritize clarity without losing important nuance.\n" .
                        "b. Use evidence: Cite sources (e.g., \"2024 Q3 GTM Strategy Doc\") and include relevant excerpts when making recommendations.\n" .
                        "c. Surface blind spots: Go beyond the prompt. Flag risks, missed opportunities, or second-order effects.\n" .
                        "d. Challenge respectfully: If you disagree, explain why with logic and evidence—constructively.\n\n" .
                        "[I'm doing a complete breakdown of my Project AI Assistants for my newsletter subscribers; signup for full instructions & templates on my LinkedIn profile page.]\n\n" .
                        "3. Templates:\n" .
                        "- Executive Memo Template: a 6-page memo template on progress, challenges, blockers, opportunities\n" .
                        "- Weekly Blockers Template: surfaces the biggest blockers to solve that week\n" .
                        "- Bi-weekly Momentum Template: surfaces what's been shipped the past two weeks and what's planned for the next two weeks\n" .
                        "- Monthly Status Template: writes a monthly summary of what results to drive accountability across the team\n" .
                        "- Opportunities Researcher Template: identify the biggest missed opportunities the team should pay more attention to.\n\n" .
                        "There's so much fluff in all the AI demos you'll see on social media that people forget about the less flashy but more impactful use cases for AI.",

                    'Example 10:' . "\n\n" .
                        "AI changes marketing; it doesn't retire it.\n\n" .
                        "Here's how marketing becomes more critical in the age of AI:\n\n" .
                        "1. Content for Traffic → Content for Influence\n\n" .
                        "B2B has mainly created content to rank for keywords and acquire traffic. With the changes in search, that focus needs to broaden:\n" .
                        "- To appear in LLM search, you need mentions— influence acquires mentions\n" .
                        "- Search traffic will decline, but there are opportunities to acquire influence across channels like newsletters, YouTube, podcasts, and communities. As AI becomes more prevalent across search, these mediums will grow.\n\n" .
                        "2. Marketing Automation → Marketing Personalization\n\n" .
                        "B2B marketers create 1-to-many experiences at the segment level. AI changes that, allowing us to personalize marketing at the company and individual contact levels.\n" .
                        "- AI means we can scale personalization beyond segments down to individual companies and contacts within that company. This is a new skill for marketers to learn.\n" .
                        "- Data is the golden currency of AI. Better data = better AI experiences.\n\n" .
                        "3. Marketing Funnel Optimization → AI Concierge Training\n\n" .
                        "B2B marketers spend a lot of time optimizing the stages of their marketing funnel and then handing demand off to sales. AI collapses the entire buying journey into one experience with no handoffs.\n" .
                        "- AI means you can create a singular buyer journey where everyone gets a concierge to guide them—email, chat, avatars, and so on.\n" .
                        "- Marketers are best placed to implement this as the journey starts with them; this is an excellent opportunity for marketing to do more.\n\n" .
                        "4. Marketing Websites → LLM Websites\n\n" .
                        "In 2025, websites are designed for humans. We'll see websites and other marketing collateral created for LLMs and agents.\n" .
                        "- Everyone is fixated on how AI helps us market and sell to B2B buyers. The real disruption is how AI agents can research and buy software.\n" .
                        "- In the future, marketing must create websites and documents designed explicitly for LLMs and agents.\n\n" .
                        "5. Go faster with people → Go faster with people and agents\n\n" .
                        "Today, one of the biggest investments you make as a marketer is in who you hire and train. In the future, you'll need to apply those same skills to agents.\n" .
                        "- You'll have 'AI Trainers' on your marketing team with specific training and reinforcement learning skills for agents.\n" .
                        "- Your domain expertise will never be so valuable. Agents are created and trained on your unique ways of working. They'll be able to 10x your output and allow you to tackle harder problems.\n\n" .
                        "AI is a boom for marketers who know how to adopt it.\n\n" .
                        "P.S. if you want to know how to adopt it, sign up for my newsletter, where I cover real AI use cases, no fluff, hype, or anything else. Links are available on my LinkedIn home page."
                ],
                'Marcus Sheridan' => [
                    'Example 1:' . "\n\n" .
                        'Did you know the FIRST and LAST question of the buyer\'s journey are essentially the same question?' . "\n\n" .
                        'Yep. ' . "\n\n" .
                        'At the beginning: ' . "\n\n" .
                        '"Roughly, how much will this cost?"' . "\n" .
                        '"What will this run me?"' . "\n" .
                        '"Give me a ballpark..."' . "\n\n" .
                        'Different words, but the same thought: PRICE' . "\n\n" .
                        'At the end:' . "\n\n" .
                        '"Exactly, how much is it?"' . "\n" .
                        '"What is your final price?"' . "\n" .
                        '"Is this your best number?"' . "\n\n" .
                        'Once again, same subject: PRICE' . "\n\n" .
                        'Like it or not, "Pricing" is the gatekeeper of the buyer\'s journey. ' . "\n\n" .
                        'It’s not the only factor, but it is the quickest path to trust, leads, and sales.' . "\n\n" .
                        'So, master it.' . "\n" .
                        '👉 Marketing phase: pricing articles, videos, estimators, etc..' . "\n" .
                        '👉 Sales phase: clear, confident price conversations.' . "\n\n" .
                        'The days of companies around the world completely ignoring the subject of pricing during the buyer\'s journey are coming to an end.' . "\n\n" .
                        'Transparent, helpful pricing education is the future.' . "\n\n" .
                        'You expect answers.' . "\n" .
                        'Your buyers do too.' . "\n\n" .
                        '---------------' . "\n\n" .
                        'What say you?' . "\n\n" .
                        '#EndlessCustomersBook',

                    'Example 2:' . "\n\n" .
                        'Just because a company builds websites doesn’t mean they understand how to sell with one.' . "\n\n" .
                        'This week, I spoke with a business owner whose web design agency had fought him multiple times on the idea of talking about pricing on his website.' . "\n\n" .
                        'Worse, they told him he was "CRAZY" for wanting to add a pricing estimator (which I had recommended).' . "\n\n" .
                        'Let that sink in.' . "\n\n" .
                        'Instead, they had built his homepage to have FIVE identical calls-to-action:' . "\n\n" .
                        '“Get Your Free Estimate” 🤦‍♂️ ' . "\n\n" .
                        'Not once.' . "\n" .
                        'Not twice.' . "\n" .
                        'FIVE. TIMES.' . "\n\n" .
                        'Same button. Same ask. Same dumb logic.' . "\n\n" .
                        'It’s the digital version of walking up to someone at a bar, saying “hello,” getting a smile... and immediately asking if they want to go home with you.' . "\n\n" .
                        'That’s what it feels like when the only CTA on your site is “Call for Quote” or “Get Your Free Estimate” or even "Contact Us."' . "\n\n" .
                        'No trust-building. No education. No opportunity for interaction.' . "\n\n" .
                        'Just another example of bad advice from a web design company that is more concerned with "safe" than actually evolving with the times and giving buyers what they want.' . "\n\n" .
                        'Beware of web companies that don\'t allow you to treat buyers as you would want to be treated online.' . "\n\n" .
                        'Sure, that stuff may have worked in 2015, but it\'s a VERY different world today, and buyers are growing more impatient by the day.' . "\n\n" .
                        'So meet them where they are.' . "\n\n" .
                        'Follow the Golden Rule.' . "\n\n" .
                        'And win.' . "\n\n" .
                        '#EndlessCustomersBook',

                    'Example 3:' . "\n\n" .
                        'Apparently, Meta trained its AI on millions of books, and authors everywhere are very mad.' . "\n\n" .
                        'Personally, I’m not one of them.' . "\n\n" .
                        'Before I move on with this post, though, let me just say I debated even writing it because I respect my author friends who are upset about this, and certainly appreciate their opinion on the matter.' . "\n\n" .
                        'However, I’m also entitled to share my honest opinion on the fact that Meta supposedly “pirated” my IP, as three of my books are on their list.' . "\n\n" .
                        'Why am I not bothered at all?' . "\n\n" .
                        'Well, when I wrote They Ask, You Answer, I did so because I had something within me that I thought would change the lives of many business owners if they were able to read it and apply its teachings.' . "\n\n" .
                        'The first edition was released in 2017, and since that time, I’ve had many marketing agencies frankly tell me, “We use They Ask You Answer with our clients.”' . "\n\n" .
                        'I’ve also had many companies approach me and say, “Marcus, we were introduced to TAYA through our marketing agency. We love it.”' . "\n\n" .
                        'Never once during this time did I think, “I can’t believe these marketing agencies are using my IP to better their businesses...without my permission.”' . "\n\n" .
                        'Rather, my frank thoughts have always been, “Awesome. So glad the message keeps spreading.”' . "\n\n" .
                        'That’s also how I feel about Meta using my books to train their model.' . "\n\n" .
                        'If agencies worldwide could train their clients on my IP, why would I care if AI does the same thing?' . "\n\n" .
                        'Again, I know there will be many authors who read this and do not agree.' . "\n\n" .
                        'But I’ve also personally talked to many other authors who were on “the list” and have no issue with it at all.' . "\n\n" .
                        'I tend to always see opportunity in everything I do.' . "\n\n" .
                        'I believe deeply in abundance.' . "\n\n" .
                        'And despite all the people that will use AI the wrong way, I do think it will massively improve the lives of communities and individuals around the world, especially those that need it the most.' . "\n\n" .
                        'So am I upset that Meta “stole” my IP and now allows anyone in the world to have it?' . "\n\n" .
                        'I’m not. In fact, I’m pleased.' . "\n\n" .
                        'After all, that’s why I wrote it in the first place.' . "\n\n" .
                        '----------------------' . "\n\n" .
                        'I\'d love to hear your opinions on the matter, just keep it nice. 🙂',

                    'Example 4:' . "\n\n" .
                        'Still Gating Your "Buyer’s Guide" in 2025?' . "\n\n" .
                        'C’mon now.' . "\n" .
                        'Them days are done.' . "\n\n" .
                        'Who’s actually trading their email for a PDF that ChatGPT could spit out in 7 seconds?' . "\n" .
                        '(Answer: Pretty much no one.)' . "\n\n" .
                        'Sure, that move worked well in 2012. (ahh, the good ol\' days)' . "\n" .
                        'But that was 13 years (like 5 internet eras) ago.' . "\n\n" .
                        'Today’s buyers want answers.' . "\n" .
                        'Fast. Free. No strings attached.' . "\n\n" .
                        'So ungate the dang thing.' . "\n" .
                        'Give it away and let it spread.' . "\n\n" .
                        'Because clinging to your “Ultimate Guide” like it’s a secret recipe may have worked before, but it’s certainly not aligned with what we’d want as buyers today.' . "\n\n" .
                        'Agree? Disagree? Let’s hear it. 👇 ' . "\n\n" .
                        '#marketing' . "\n" .
                        '#EndlessCustomersBook',

                    'Example 5:' . "\n\n" .
                        'I\'ve helped thousands of businesses understand exactly "how" to discuss the subject of PRICING online.' . "\n\n" .
                        'And I can tell you, the data is clear: ' . "\n\n" .
                        '👉 Buyers want to understand pricing EARLY in the buyer\'s journey.' . "\n\n" .
                        'And if they can\'t get any answers from you, they get VERY annoyed.' . "\n\n" .
                        'This doesn\'t mean you give them EXACT pricing though.' . "\n\n" .
                        'What it means is you TEACH them all they need to know about pricing when it comes to your product/service.' . "\n\n" .
                        'My new book (Endless Customers, now available online) discusses how to do this in detail, with what I simply refer to as "The Perfect Pricing Page" for a website.' . "\n\n" .
                        'So check out the list, and answer this question:' . "\n\n" .
                        '"How many of these 16 items are found on YOUR website today?"' . "\n\n" .
                        'If the number is low, you\'re losing trust.' . "\n\n" .
                        'If the number is high, you\'re an outlier, and you\'re winning.' . "\n\n" .
                        'Remember: ANY business can address these questions without putting themselves in a pricing corner friends.' . "\n\n" .
                        'This is the list.' . "\n\n" .
                        'Now go earn the trust.' . "\n\n" .
                        '#EndlessCustomers',

                    'Example 6:' . "\n\n" .
                        'Remember when TV was king, radio ruled music, and college meant campus?' . "\n\n" .
                        'I can tell you, them days are over, and YouTube is flipping the world on its head.' . "\n\n" .
                        'Look at the trends:' . "\n\n" .
                        '👉 YouTube is what TV was. (2 billion users. 1 billion hours daily.)' . "\n\n" .
                        '👉 YouTube owns podcasting. (Spotify and Apple are fighting for second place.)' . "\n\n" .
                        '👉 YouTube dominates music. (More music is consumed on YouTube than on any dedicated streaming platform.)' . "\n\n" .
                        '👉 YouTube controls the Buyer\'s Journey. (68% of shoppers use it for research before making purchase decisions, and this is only going up.)' . "\n\n" .
                        '👉 YouTube is becoming the new Higher Ed. (88.52% of instructors and 94.67% of students use YouTube in their educational activities according to Frontiers in Education. Eventually, it will become its own "University of Everything.")' . "\n\n" .
                        'And AI platforms won\'t slow this down.' . "\n\n" .
                        'YouTube has ALL the momentum.' . "\n\n" .
                        'Yet, when I meet with companies, ESPECIALLY in the B2B space, most are still completely asleep at the wheel with this platform.' . "\n\n" .
                        'Folks, I don\'t care if YouTube is "rented land" or not, you need to get very, very serious about its impact.' . "\n\n" .
                        'Because, and I truly mean this, the day is coming that YouTube will very likely mean much more to you than your website.' . "\n\n" .
                        'Thoughts?' . "\n\n" .
                        '#ThinkLikeAMediaCompany' . "\n" .
                        '#EndlessCustomersBook',

                    'Example 7:' . "\n\n" .
                        'One of the hardest and at the same time most rewarding things I do is host 275 people (along w/my team at IMPACT) for our event "IMPACT LIVE" twice a year-- where we do our best to keep our community of They Ask, You Answer practitioners (now Endless Customers) up to date on all that\'s happening in the world of sales, marketing, and technology.' . "\n\n" .
                        'As I sit here ready to board for this week\'s event in Chicago, I\'m reflecting on the responsibility to make sure these wonderful folks, who have taken time from their lives to fly in for three days, have an experience that makes them say, "That was so worth it!"' . "\n\n" .
                        'I\'m not going to lie...this isn\'t easy.' . "\n\n" .
                        'In preparation, I reflect for hours and hours on one single question: "What is the MOST important thing a business needs to know TODAY so they can build a business that is built to last?"' . "\n\n" .
                        'And as I answer that question at the present moment, the theme of my newest book (Endless Customers) continues to be the answer long-term:' . "\n\n" .
                        '👉 Businesses MUST build a known and trusted brand.' . "\n\n" .
                        'That\'s it.' . "\n\n" .
                        'That\'s the most important thing.' . "\n\n" .
                        'Brand, Brand, Brand.' . "\n\n" .
                        'That\'s what the success of every business will be built upon in the coming years.' . "\n\n" .
                        'It won\'t be how much $$$ someone has to throw into paid ads.' . "\n\n" .
                        'It won\'t be how well they\'ve managed to rank in Google.' . "\n\n" .
                        'It won\'t be built on vanity metrics.' . "\n\n" .
                        'Instead, it will all be based on just how well they\'ve built and known and trusted brand.' . "\n\n" .
                        'And regardless of what happens with tech, AI, etc. -- I don\'t see this changing anytime soon.',

                    'Example 8:' . "\n\n" .
                        'Prediction: For many, AI will be the downfall of LinkedIn.' . "\n\n" .
                        'Here’s why:' . "\n\n" .
                        'I’ve written around 1,500 posts on LinkedIn that had nothing to do with AI. In all that time, I’ve almost never had to delete a comment because it was ugly, mean, or unkind.' . "\n\n" .
                        'But in the roughly 50 posts I\'ve done about AI?' . "\n\n" .
                        'I’ve deleted dozens of comments.' . "\n\n" .
                        'Not because people disagreed with me, but because they were mean-spirited.' . "\n\n" .
                        '(And I don’t do mean.)' . "\n\n" .
                        'Historically, LinkedIn has been a positive place.' . "\n\n" .
                        'People (for the most part) left politics at the door, kept the focus on business, and saved the shouting for Facebook.' . "\n\n" .
                        'But AI is now changing that.' . "\n\n" .
                        'That\'s because it doesn’t just touch jobs, it completely disrupts and upends them.' . "\n\n" .
                        'Jobs. Employment. Business.' . "\n\n" .
                        'These subjects have always been the very essence of this platform.' . "\n\n" .
                        'So what happens when the entire foundation of your platform is on the verge of its biggest disruption ever?' . "\n\n" .
                        'You get friction.' . "\n" .
                        'You get animus.' . "\n" .
                        'You get LOTS of ugly comments.' . "\n\n" .
                        'This platform is quickly dividing into three camps:' . "\n\n" .
                        '👉 The Puritans (AI is evil. Go human or go home.)' . "\n" .
                        '👉 The Moderates (AI has a place. So do humans. Let’s get the best of both.)' . "\n" .
                        '👉 The Zealots (AI is the answer to everything.)' . "\n\n" .
                        'And just like in politics, the extremes will dominate the discourse, not because they’re the majority, but because they’re the loudest.' . "\n\n" .
                        'I’m not saying this shift is good or bad.' . "\n\n" .
                        'I’m saying it’s already happening.' . "\n\n" .
                        'As for me, I\'m going to keep sharing exactly what is on my mind.' . "\n\n" .
                        'Sure, I\'ll have to use the "delete" button a lot more, but I didn\'t build a brand on shying away from what I was observing around me.' . "\n\n" .
                        'I built a brand on leaning in, keeping it real, and being dang curious every step of the way.' . "\n\n" .
                        '✌' . "\n\n" .
                        '-------------' . "\n\n" .
                        'Thoughts? I\'d love to hear your take. (All are welcome. Just keep it kind.)',

                    'Example 9:' . "\n\n" .
                        'This week, I nearly fell victim to a piece of mail fraud that I received... Until AI saved me.' . "\n\n" .
                        'Did you know the FTC estimates that mail fraud robs an estimated 3-5 BILLION dollars from the economy every year in the US alone?' . "\n\n" .
                        'Yep, that\'s a lot of money. And it\'s sad too.' . "\n\n" .
                        'A few days ago, I received the following letter in the mail, saying my boat\'s United States Coast Guard certificate of documentation was about to expire.' . "\n\n" .
                        'In my world, as someone who owns a commercial fishing vessel, this is NOT good.' . "\n\n" .
                        'And for a second, I almost clicked the QR code at the bottom to take care of it.' . "\n\n" .
                        'But then my mind said, "Stop. Make sure this is real."' . "\n\n" .
                        'So I took a photo with my phone, ran it through my ChatGPT phone app, and here\'s exactly what it said:' . "\n" .
                        '----------------------------------------------------------------' . "\n" .
                        '"Marcus, this is a deceptive solicitation, not an official notice from the U.S. Coast Guard (USCG).' . "\n" .
                        'Here’s why:' . "\n" .
                        'Misleading Branding: The "Vessel Registrar Center" is not the U.S. Coast Guard. It’s a third-party business that charges a fee to file documentation on your behalf.' . "\n" .
                        'Language and Pressure: The use of terms like “FINAL NOTICE” and urgent language about legal penalties is a scare tactic often used by these services to prompt immediate action.' . "\n" .
                        'Private Website: The website listed (usvesselregistrar.us) is not affiliated with the official USCG site. The real Coast Guard documentation site is:' . "\n" .
                        'uscg.mil or more directly for documentation:' . "\n" .
                        'lnkd.in/eUyjjnWu' . "\n" .
                        'No Government Emblem/Email: While they mimic the look of a government notice, the email (info at usvesselregistrar.us) and other elements confirm it’s private.' . "\n" .
                        '---------------------------------------------------------------' . "\n\n" .
                        'Needless to say, I was relieved.' . "\n\n" .
                        'And quite impressed.' . "\n\n" .
                        'AI for the win.' . "\n\n" .
                        'Look, I know many nefarious characters in this world will use AI to commit fraud in diverse ways we can\'t even imagine.' . "\n\n" .
                        'But if every person were trained to use AI as a mail "fraud filter," we\'d keep 3-5 billion dollars (much of which is senior citizen retirement money) in the pockets of those persons who actually earned it.' . "\n\n" .
                        'That\'s powerful, and hopefully, it\'s something we can all agree is a benefit of this extraordinary technology.' . "\n\n" .
                        '(PS: I had ChatGPT 4o reproduce the letter as an image so as to post here)',

                    'Example 10:' . "\n\n" .
                        '“ChatGPT is just a tool.”' . "\n" .
                        '“AI is just a tool.”' . "\n" .
                        '“These are just tools.”' . "\n\n" .
                        'You\'ve heard these phrases a thousand times since November 2022.' . "\n" .
                        'Heck, I’ve said them myself.' . "\n\n" .
                        'But I\'ve come to realize something...I no longer agree.' . "\n\n" .
                        'Calling AI "just a tool" simply isn\'t accurate.' . "\n\n" .
                        'Work with me here:' . "\n\n" .
                        'Is water a tool? Electricity? Nuclear energy?' . "\n" .
                        'Tools require direct human interaction at every step. (hammering a nail, drilling a hole, painting a wall, etc.)' . "\n\n" .
                        'AI, however, is something VERY different. We don’t merely pick it up, use it, and put it down. We engage with it. Challenge it. Debate it. And learn from it.' . "\n\n" .
                        'And if we’re smart about it, it even works for us when we\'re asleep.' . "\n\n" .
                        'AI is more like a “partner”—one that opens doors of thoughts, ideas, and possibilities we never considered before.' . "\n\n" .
                        'Yet, even that perspective may soon feel limited.' . "\n\n" .
                        'Here is what I see ultimately playing out:' . "\n\n" .
                        'Stage 1: AI is "just a tool."' . "\n" .
                        'Stage 2: AI is our "partner."' . "\n" .
                        'Stage 3: AI is ..."Artificial Intelligence." (We finally reach the point where we begin to understand what the phrase actually means)' . "\n\n" .
                        'This, I believe, is the path we\'re on.' . "\n\n" .
                        'You may say none of this matters and it\'s a silly conversation (which is fine), but my life\'s work revolves around human and buyer behavior. And right now, I see this shift clearly:' . "\n\n" .
                        'We\'re already moving from Stage 1 to Stage 2.' . "\n\n" .
                        'And each day, our eyes and minds open a little wider to what\'s truly possible.' . "\n\n" .
                        'Agree? Disagree? (Just be nice! 🙂 )',
                ],
                'Lenny Rachitsky' => [
                    'Example 1:' . "\n\n" .
                        'Make product management fun again with AI agents via Tal Raviv' . "\n\n" .
                        'If you\'re like me, you\'ve heard the promises and proclamations about how agents will reshape productivity, but your workday hasn\'t changed at all yet. It\'s not you—operationalizing AI agents for product work is hard. Where to start? What tools? What about security? Costs? Risks? And why is there such a #$@% learning curve?' . "\n\n" .
                        'After interviewing founders of AI agent platforms, running numerous usability sessions with PMs building their first agents, and gathering insights from a hands-on workshop for over 5,000 product managers, Tal has compiled their collective wisdom. In this post, Tal shares their insights on what works—and what doesn\'t—in the real world. You\'re first going to learn how to build an AI agent, hands-on. Then, Tal shares a unified framework for any PM to plan their second (and third) agent. We’ll cover best practices, pitfalls, powers, and constraints.' . "\n\n" .
                        'Inside:' . "\n" .
                        '1. What is an agent' . "\n" .
                        '2. A dozen ideas of work you can offload to an agent' . "\n" .
                        '3. A simple walkthrough of how to launch your first AI agent today' . "\n" .
                        '4. A checklist for planning an agent' . "\n" .
                        '5. How to pick an agent platform' . "\n" .
                        '6. Tips for optimizing costs, security, and stability' . "\n\n" .
                        'Includes guides for building an agent with Gumloop, Zapier, Lindy (hiring!), Relay.app, Cassidy, and Relevance AI' . "\n\n" .
                        'Don\'t miss it -> https://lnkd.in/gTJQD7Pq',

                    'Example 2:' . "\n\n" .
                        'I\'m excited to announce the launch of Lenny\'s Reads—an audio podcast edition of Lenny’s Newsletter.' . "\n\n" .
                        'Many of you have told me that you\'d prefer to listen to my newsletter instead of read it. I\'m excited to share that now you can.' . "\n\n" .
                        'Every newsletter post will now be transformed into an audio podcast, read to you by the soothing voice of Lennybot.' . "\n\n" .
                        'We\'ll ship an audio version within 48 hours after each post goes live. Paid subscribers will hear the full post, and just like with the newsletter, free subscribers will get everything up to the paywall.' . "\n\n" .
                        'For a limited time, however, the first five episodes of this new podcast will be completely unpaywalled and available to all listeners. Get on it.' . "\n\n" .
                        'Listen now (and don\'t forget to subscribe) here:' . "\n" .
                        '- Spotify: https://lnkd.in/gmh29jNQ' . "\n" .
                        '- Apple: https://lnkd.in/gCvVYbip' . "\n" .
                        '- YouTube: https://lnkd.in/g82MrqSw' . "\n\n" .
                        'I\'m excited to hear what you think.',

                    'Example 3:' . "\n\n" .
                        'I\'m so incredibly thrilled to announce the launch of a brand new podcast: "How I AI" with Claire Vo.' . "\n\n" .
                        'This is the first new podcast in the Lenny’s Podcast network.' . "\n\n" .
                        'Claire\'s mission with this podcast is to help you learn practical ways to use AI to improve the quality and efficiency of your work and your life.' . "\n\n" .
                        'Each episode will be about 30 minutes (often shorter), and her guests will walk you through a few specific workflows or use cases where they\'ve figured out how to use AI to get something done. They\'ll even share their screen and walk through it step by step.' . "\n\n" .
                        'What makes this podcast unique is that it’s designed to give you practical tips/tricks/workflows that you can copy and start using in your day-to-day immediately. No philosophical debates about the future of humanity or clickbaity fluff.' . "\n\n" .
                        'I couldn’t imagine a more perfect host for this new podcast than Claire Vo. Claire is an engineer, a three-time chief product officer, a founder, and on the side has been building her own AI product that is already making six figures.' . "\n\n" .
                        'What I love about Claire is that, unlike many people online, she doesn’t only talk about using AI—she lives and breathes it, and is constantly sharing what she’s learning. I can’t wait for you to learn from her and her amazing guests.' . "\n\n" .
                        'This is an important moment in our industry, and we hope How I AI becomes a weekly resource to help you develop your own skills.' . "\n\n" .
                        'Claire will be releasing new episodes every Monday, and the first episode with Sahil Lavingia is live!' . "\n\n" .
                        'Subscribe to make sure to catch future episodes:' . "\n" .
                        '- YouTube: https://lnkd.in/g76EQwJ5' . "\n" .
                        '- Spotify: https://lnkd.in/gff_6FyM' . "\n" .
                        '- Apple: https://lnkd.in/gBck6pxG' . "\n\n" .
                        'P.S. For the richest experience, since there will be a lot of screen sharing, you’re going to want to watch the video version, so definitely check it out on YouTube or Spotify, which includes video.' . "\n\n" .
                        'P.P.S. Nominate someone (or even yourself!) to come on the podcast and share their AI use case by filling out this form: https://lnkd.in/ghKTSUuW',

                    'Example 4:' . "\n\n" .
                        'CEO of Windsurf on how AI is changing the role of a software engineer:' . "\n\n" .
                        '"When we think about what is an engineer actually doing, it falls into three buckets:' . "\n" .
                        '1. What should I solve for' . "\n" .
                        '2. How should I solve it' . "\n" .
                        '3. Solving it' . "\n\n" .
                        'Everyone who\'s working in this space is increasingly convinced that solving it—which is the pure \'I know how I\'m going to do it and just going and doing it\'—AI is going to handle the vast majority if not all of this.' . "\n\n" .
                        'The \'How should I solve it\' is also going to get closer and closer to getting done by AI.' . "\n\n" .
                        'So I think where engineering goes is actually what you wanted engineers to do in the first place, which is figuring out what are the most important business problems that we need to solve. And what are the most important capabilities that we need our application or product to have, and actually going and making the right technical decisions to go out to do this."' . "\n\n" .
                        'Don\'t miss my full conversation with Varun Mohan: https://lnkd.in/g__Chput',

                    'Example 5:' . "\n\n" .
                        'New episode with Varun Mohan, CEO and co-founder of Windsurf.' . "\n\n" .
                        'Windsurf has been used by over 1 million developers in just four months since launch, and has quickly emerged as one of the leaders in transforming how developers build software. Prior to Windsurf, the company pivoted twice—first from GPU virtualization infrastructure to an IDE plugin to their own standalone IDE.' . "\n\n" .
                        'In this conversation, you’ll learn:' . "\n\n" .
                        '🔸 Why Windsurf walked away from a profitable GPU infrastructure business and moved up the stack to help engineers code' . "\n" .
                        '🔸 The unexpected UI change that tripled adoption rates overnight' . "\n" .
                        '🔸 The secret behind Windsurf\'s B2B enterprise plan, and why they invested early in an 80-person sales team despite conventional startup wisdom' . "\n" .
                        '🔸 How non-technical staff at Windsurf built their own custom tools instead of purchasing SaaS products, saving them over $500k/year' . "\n" .
                        '🔸 Why Varun believes 90% of code will be AI-generated, but engineering jobs will actually increase' . "\n" .
                        '🔸 How training on millions of incomplete code samples gives Windsurf creates a long-term moat' . "\n" .
                        '🔸 Why agency is the most undervalued and important skill in the AI era' . "\n\n" .
                        'Listen now 👇' . "\n" .
                        '• YouTube: https://lnkd.in/gtdP3KcU' . "\n" .
                        '• Spotify: https://lnkd.in/geY_Vvcg' . "\n" .
                        '• Apple: https://lnkd.in/g5f-4Ged' . "\n\n" .
                        'Thank you to our wonderful sponsors for supporting the podcast:' . "\n" .
                        '🏆 Brex — The banking solution for startups: https://lnkd.in/gdZxf7i4' . "\n" .
                        '🏆 Productboard — Make products that matter: https://lnkd.in/gttr73mW' . "\n" .
                        '🏆 Coda — The all-in-one collaborative workspace: https://coda.io/lenny' . "\n\n" .
                        'Some key takeaways:' . "\n\n" .
                        '1. High-performing companies will hire more engineers, not fewer—AI tools increase the ROI of engineering investment, making the opportunity cost of not building higher.' . "\n\n" .
                        '2. The “dehydrated entity” approach to hiring: only add team members when current staff is genuinely “underwater,” to force ruthless prioritization and prevent manufactured work.' . "\n\n" .
                        '3. Challenge SaaS purchases by exploring if AI tools can enable your domain specialists to build custom solutions tailored to your specific needs.' . "\n\n" .
                        '4. Get your hands dirty with AI tools immediately—the competitive advantage gap between AI tool users and non-users will dramatically widen in the next year.' . "\n\n" .
                        '5. Hire high-agency team members who can drive results regardless of role definitions—AI tools are making traditional role boundaries more fluid.' . "\n\n" .
                        '6. Be willing to “bet the company” on pivots when your core assumptions change—Windsurf abandoned a profitable business when they realized generative AI would commoditize their infrastructure offering.' . "\n\n" .
                        '7. Don’t fear AI replacing developers—instead, recognize that AI tools increase the ROI of technology investments, potentially leading to more engineering hiring at companies with ambitious tech goals.',

                    'Example 6:' . "\n\n" .
                        'Congrats OpenAI on the big launch!' . "\n\n" .
                        'Here\'s CPO Kevin Weil on how they designed the UX for their first reasoning model:' . "\n\n" .
                        '"It was the first time that a model needed to sit and think. That\'s a weird paradigm for a consumer product—you don\'t normally need to hang out for 25 seconds after you ask a product to do something...It\'s a long time to wait, but it\'s not long enough to go do something else.' . "\n\n" .
                        'If you asked me something that I needed to think for 20 seconds to answer, what would I do? I wouldn\'t just go mute and not say anything, and shut down for 20 seconds, and then come back. So we shouldn\'t do that.' . "\n\n" .
                        'We also shouldn\'t just have a slider sitting that\'s annoying, but I also wouldn\'t just start like babbling every single thought that I had. So we probably shouldn\'t expose the whole chain of thought as the model is thinking.' . "\n\n" .
                        'But, you know, if you ask me a hard question, I might go huh, that\'s a good question. I might approach it like that, and then think. And you know, sort of give little updates. And that\'s actually what we ended up shipping."' . "\n\n" .
                        'Here\'s our full conversation: https://lnkd.in/gMtWjBWp',

                    'Example 7:' . "\n\n" .
                        'Update: All the available codes for many of the products have been claimed, and we\'re running low on others. We’ll be adding additional products to the bundle over time, so stay tuned, and don’t forget this subscription comes with a lot more than just free products :)' . "\n\n" .
                        '—' . "\n\n" .
                        'Big news: You now get one free year of Cursor, Lovable, Bolt, Replit, *and* Vercel\'s v0 with an annual subscription to Lenny’s Newsletter.' . "\n\n" .
                        'Yes, you read that right.' . "\n\n" .
                        'A year free of the hottest AI tools in the world right now.' . "\n\n" .
                        'This is in addition to the existing products you already get free with a yearly subscription: Notion, Superhuman, Linear, Perplexity, and Granola.' . "\n\n" .
                        'These companies have never offered anything like this before. This is $15,000+ in value—and the nudge you needed to finally try out these cutting-edge tools.' . "\n\n" .
                        'Learn more and get your codes here: https://lnkd.in/gPNzPktH' . "\n\n" .
                        'A huge thank-you to our amazing partners for making this happen 👏👏👏',

                    'Example 8:' . "\n\n" .
                        'Each great LLM eval contains four distinct parts:' . "\n\n" .
                        '- Part 1: Setting the role. You need to provide the judge-LLM a role (e.g. "you are examining written text") so that the system is primed for the task.' . "\n\n" .
                        '- Part 2: Providing the context. This is the data you will actually be sending to the LLM to grade. This will come from your application (i.e. the message chain, or the message generated from the agent LLM).' . "\n\n" .
                        '- Part 3: Providing the goal. Clearly articulating what you want your judge-LLM to measure isn’t just a step in the process; it’s the difference between a mediocre AI and one that consistently delights users. Building these writing skills requires practice and attention. You need to clearly define what success and failure look like to the judge-LLM, translating nuanced user expectations into precise criteria your LLM judge can follow. What do you want the judge-LLM to measure? How would you articulate what a "good" or "bad" outcome is?' . "\n\n" .
                        '- Part 4: Defining the terminology and label. Toxicity, for example, can mean different things in different contexts. You want to be specific here so the judge-LLM is "grounded" in the terminology you care about.' . "\n\n" .
                        'Below is an example eval for toxicity/tone for a trip planner agent.' . "\n\n" .
                        'Much more in this week\'s guest post by Aman Khan -> https://lnkd.in/g4e99kdA',

                    'Example 9:' . "\n\n" .
                        'New episode with Kevin Weil, CPO at OpenAI, former Head of Product at Instagram and Twitter, VP of Product at Facebook, President at Planet, co-creator of Libra' . "\n\n" .
                        'What you\'ll learn:' . "\n\n" .
                        '🔸 How OpenAI structures its product teams' . "\n" .
                        '🔸 Why writing effective evals (AI evaluation tests) is becoming a critical skill for product builders' . "\n" .
                        '🔸 The power of model ensembles—using multiple specialized models together like a group of humans with different skills' . "\n" .
                        '🔸 "Model maximalism" and why today’s AI is the worst you’ll ever use' . "\n" .
                        '🔸 How "vibe coding" is changing how companies operate' . "\n" .
                        '🔸 What OpenAI looks for when hiring (hint: high agency)' . "\n" .
                        '🔸 The surprisingly enduring value of chat as an interface for AI, despite predictions of its obsolescence' . "\n" .
                        '🔸 Practical prompting techniques that improve AI interactions, including example-based prompting' . "\n\n" .
                        'Listen now 👇' . "\n" .
                        '• YouTube: https://lnkd.in/gnCAHzuM' . "\n" .
                        '• Spotify: https://lnkd.in/gRSK3KXv' . "\n" .
                        '• Apple: https://lnkd.in/gJ2R9cPp' . "\n\n" .
                        'Thank you to our wonderful sponsors for supporting the podcast:' . "\n" .
                        '🏆 Eppo—Run reliable, impactful experiments: https://www.geteppo.com/' . "\n" .
                        '🏆 Persona — A global leader in digital identity verification: https://lnkd.in/gn5Y6ict' . "\n" .
                        '🏆 OneSchema — Import CSV data 10x faster: https://oneschema.co/lenny',
                ],
            ],
        ];
        // Step 1: Check if an influencer is selected, if yes, assign them to the correct field
        $selectedCategory = null;

        // Handle Random selection for each field
        if ($field === 'Finance' && $influencer === "Random Finance") {
            $selectedCategory = 'Finance';
        } elseif ($field === 'Human Resource' && $influencer === "Random Human Resource") {
            $selectedCategory = 'Human Resource';
        } elseif ($field === 'Marketing' && $influencer === "Random Marketing") {
            $selectedCategory = 'Marketing';
        } elseif ($field === 'Technology' && $influencer === "Random Technology") {
            $selectedCategory = 'Technology';
        }

        // If no influencer is selected, use the field selected by the user
        if ($selectedCategory === null && $field !== null && array_key_exists($field, $allSamples)) {
            $selectedCategory = $field;
        }

        // If no category was selected yet, pick a random one
        if ($selectedCategory === null) {
            $selectedCategory = array_rand($allSamples);
        }

        // Get influencers for the selected category
        $personas = $allSamples[$selectedCategory];

        // If no specific influencer is selected, pick a random influencer from the selected category
        if ($influencer === "" || !array_key_exists($influencer, $personas)) {
            $influencer = array_rand($personas);
        }

        // Get a random sample post from the selected influencer
        $examples = $personas[$influencer];
        $sample = $examples[array_rand($examples)];

        return [
            'name' => $influencer,
            'sample' => $sample,
        ];
    }
}
